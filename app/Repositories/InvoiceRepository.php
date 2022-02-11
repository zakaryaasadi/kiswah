<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\{Invoice, School, Teacher};
use App\Repositories\BaseRepository;
use App\Services\CapiCollect;
use Illuminate\Support\Str;

class InvoiceRepository extends BaseRepository
{
    private $invoice, $school, $teacher, $teacher_cost, $capiCollect;

    public function __construct(Invoice $invoice, School $school, Teacher $teacher, CapiCollect $capiCollect)
    {
        parent::__construct($invoice);
        $this->invoice = $invoice;
        $this->school = $school;
        $this->teacher = $teacher;
        $this->capiCollect = $capiCollect;
        $this->teacher_cost = 750;
    }

    public function getByUuid(string $uuid)
    {
        return $this->invoice->findByUuid($uuid)->first();
    }

    public function findByUuid(string $uuid)
    {
        return $this->invoice->findByUuid($uuid)->firstOrFail();
    }

    public function findByCapiRef(string $capiRef)
    {
        return $this->invoice->findByCapiRef($capiRef);
    }
//
//    public function generateInvoice()
//    {
//        $teachers = $this->teacher->invoicable()->get();
//        $schools = $this->school->invoicable()->get();
//        foreach ($teachers as $teacher) {
//            $invoice_title = "Teachify Monthly Invoice for $teacher->name";
//            $data = $this->formatInvoiceData('teacher', $invoice_title, $this->teacher_cost, $teacher->id);
//            $capiInvoice = $this->generateCapiCollectInvoice($teacher, $data);
//            $data['capi_reference'] = $capiInvoice->data['reference'];
//            $this->create($data);
//
//        }
//
//        foreach ($schools as $school) {
//            $invoice_title = "Teachify Monthly Invoice for All Teachers in $school->name";
//            $amount = $this->teacher_cost * $school->teacher_count;
//            $data = $this->formatInvoiceData('school', $invoice_title, $amount, $school->id);
//            $capiInvoice = $this->generateCapiCollectInvoice($school, $data);
//            $data['capi_reference'] = $capiInvoice->data['reference'];
//            $this->create($data);
//
//        }
//    }


    public function generateTestMonthlyInvoice()
    {
        $schools = $this->school->testable()->get();
        $school_count = 1;
        print "Start sending";
        print "\n Sending to " . $schools->count() . " schools";
        foreach ($schools as $school) {
            $invoice_title = "Teachify Monthly Invoice for All Teachers in $school->name";
            $amount = 0;
            $teacher_type = $school->teachers()->groupBy('exam')
                ->select(DB::raw('exam,count(*) as total_teachers'))
//                ->selectRaw('count(*) as total_teachers, exam')
                ->pluck('total_teachers', 'exam');
//                ->get();
            $teachers_snapshot = $school->teachers()->get(['id', 'uuid', 'last_name', 'first_name', 'middle_name'])
                ->each->setAppends(['name', 'payable'])->makeHidden(['last_name', 'first_name', 'middle_name'])
                ->toArray();
            $tc = 0;
            foreach ($teacher_type as $key => $count) {
                $tc += $count;
                $amount += (TEACHER_AMOUNT[$key] ?? 750) * $count;
            }
            $invoice_id = $school->uuid . '-' . now()->format('y') . '-' . now()->format('m');
            $data = $this->formatInvoiceData('school', $invoice_title, $amount,
                $school->id, $invoice_id, $teachers_snapshot);
            $capiInvoice = $this->generateCapiCollectInvoice($school, $data);
            if ($capiInvoice->success)
                $data['capi_reference'] = $capiInvoice->data['reference'];
            $this->create($data);
            $msg = "Dear HoS, " . now()->format('M') . " " . now()->format('Y') .
                " Invoice ID: $invoice_id Pay ₦" . number_format($amount, 0) . " for $tc Teachers at any bank on E-BILLS PAY. https://teachify.ng/portal/invoice for details";
            sendBulkSms($school->active_phone, $msg);
            echo "\rSent Message to school NO. " . $school_count . "/" . $schools->count();
            $school_count++;
        }
        print "\nDone sending";
        return null;
    }

    public function generateMonthlyInvoice()
    {
        $schools = $this->school->invoicable()->get();
        $school_count = 1;
        print "Start sending";
        print "\n Sending to " . $schools->count() . " schools";
        foreach ($schools as $school) {
            $invoice_title = "Teachify Monthly Invoice for All Teachers in $school->name";
            $amount = 100;
            $teacher_type = $school->teachers()->groupBy('exam')->select(DB::raw('exam,count(*) as total_teachers'))->pluck('total_teachers', 'exam');
            $teachers_snapshot = $school->teachers()->get(['id', 'uuid', 'last_name', 'exam', 'first_name', 'middle_name'])->each->setAppends(['name', 'payable'])->makeHidden(['last_name', 'first_name', 'middle_name'])->toArray();

            $tc = 0;
            foreach ($teacher_type as $key => $count) {
                $tc += $count;
                $amount += (TEACHER_AMOUNT[$key] ?? 750) * $count;
            }
            $invoice_id = $school->uuid . '-' . now()->format('y') . '-' . now()->format('m');
            $data = $this->formatInvoiceData('school', $invoice_title, $amount,
                $school->id, $invoice_id, $teachers_snapshot);
//            $capiInvoice = $this->generateCapiCollectInvoice($school, $data);
//            if ($capiInvoice->success)
//                $data['capi_reference'] = $capiInvoice->data['reference'];

            $this->create($data);
            $msg = "Dear HoS, " . now()->format('M') . " " . now()->format('Y') .
                " Invoice ID: $invoice_id Pay N" . number_format($amount, 0) . " for $tc Teachers at any bank on E-BILLS PAY. https://teachify.ng/portal/invoice for details";
            sendSms($school->active_phone, $msg);
            echo "\rSent Message to school NO. " . $school_count . "/" . $schools->count();
            $school_count++;
        }
        print "\nDone sending";
        return null;
    }

    public function updateInvoiceDetails()
    {
        $invoices = Invoice::where('relation', '!=', 213)->where('relation', '!=', 1584)->get();
        $school_count = 1;
        print "\rGenerating tool";
        echo "\nEditing to " . $invoices->count() . " schools";
        foreach ($invoices as $invoice) {
            $school = $invoice->school;
            $amount = 100;
            $teacher_type = $school->teachers()->groupBy('exam')->select(DB::raw('exam,count(*) as total_teachers'))->pluck('total_teachers', 'exam');
            $tc = 0;
            foreach ($teacher_type as $key => $count) {
                $tc += $count;
                $amount += (TEACHER_AMOUNT[$key] ?? 750) * $count;
            }
            $invoice->snapshot = $school->teachers()->get(['id', 'uuid', 'last_name', 'exam', 'first_name', 'middle_name'])->each->setAppends(['name', 'payable'])->makeHidden(['last_name', 'first_name', 'middle_name'])->toArray();
            if (!$invoice->is_paid) {
                echo "\nEditing to " . $invoices->count() . " schools";
                $invoice->amount = $amount;
            }
            $invoice->save();
            echo "\rEdited to invoice NO. " . $school_count . "/" . $invoices->count();
            $school_count++;
        }
        print "\nDone Editing";
        return null;
    }


    public function generateTestInvoice()
    {
        $schools = $this->school->where('name', 'Test School')->get();
        foreach ($schools as $school) {
            $invoice_title = "Teachify Monthly Invoice for All Teachers in $school->name";
            $amount = 750;// $this->teacher_cost * $school->teacher_count;
            $data = $this->formatInvoiceData('school', $invoice_title, $amount, $school->id);
            $capiInvoice = $this->generateCapiCollectInvoice($school, $data);
            $data['capi_reference'] = $capiInvoice->data['reference'];
            $this->create($data);
        }
    }

    protected function formatInvoiceData($type, $description, $amount, $relation, $invoice_id = null, $snapshot = [])
    {
        return [
            'uuid' => $invoice_id ? $invoice_id : $this->generateUuid(),
            'type' => $type,
            'description' => $description,
            'amount' => $amount,
            'relation' => $relation,
            'snapshot' => $snapshot
        ];
    }

    private function uuid(): string
    {
        $HEX = range('0', '9');
        $form_id = 'TN-PAY';
        for ($i = 0; $i < 8; $i++) {
            $form_id .= $HEX[random_int(0, 9)];
        }
        return $form_id;
    }

    private function generateUuid(): string
    {
        $s = null;
        while ($s === null) {
            $id = $this->uuid();
            if (!Invoice::where('uuid', $id)->exists()) $s = $id;
        }
        return $s;
    }

    private function generateCapiCollectInvoice(object $customer, array $data)
    {
        if (!$this->capiCollect->showCustomer($customer->email)->success) {
            $this->capiCollect->addCustomer([
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->active_phone
            ]);
        }
        return $this->capiCollect->addInvoice([
            'customer_email' => $customer->email,
            'amount' => $data['amount'],
            'description' => $data['description']
        ]);
    }

}
