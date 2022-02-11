<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\{Invoice, Logger, School, Teacher, User};
use App\Repositories\BaseRepository;
use App\Services\CapiCollect;
use Illuminate\Support\Str;

class LoggerRepository extends BaseRepository
{
    private $logger;

    public function __construct(Logger $logger)
    {
        parent::__construct($logger);
        $this->logger = $logger;
    }


    public function newLog($action = null, $table = null, $relation = null, $params = null)
    {
        return $this->create(['params' => $params, 'action' => $action, 'table' => $table, 'relation' => $relation]);
    }

    public function findAdminLogs($id)
    {
        return $this->logger->where('table', 'users')->where('relation', $id)->get();
    }

    public function findInvoiceLogs($id)
    {
        return $this->logger->where('table', 'invoices')->where('relation', $id)->get();
    }

    public function findTeacherLogs($id)
    {
        return $this->logger->where('table', 'teachers')->where('relation', $id)->get();
    }

    public function findSchoolLogs($id)
    {
        return $this->logger->where('table', 'schools')->where('relation', $id)->get();
    }

    public function findTransactionLogs($id)
    {
        return $this->logger->where('table', 'transactions')->where('relation', $id)->get();
    }


    public function allAdminLogs()
    {
        return $this->logger->where('table', 'users')->get();
    }

    public function allInvoiceLogs()
    {
        return $this->logger->where('table', 'invoices')->get();
    }

    public function allTeacherLogs()
    {
        return $this->logger->where('table', 'teachers')->get();
    }

    public function allSchoolLogs()
    {
        return $this->logger->where('table', 'schools')->get();
    }

    public function allTransactionLogs()
    {
        return $this->logger->where('table', 'transactions')->get();
    }

    public function saveAdminLog($action, $id = null, $params = null)
    {
        return $this->newLog($action, 'users', $id, $params);
    }

    public function saveInvoiceLog($action, $id = null, $params = null)
    {
        return $this->newLog($action, 'invoices', $id, $params);
    }

    public function saveTeacherLog($action, $id = null, $params = null)
    {
        return $this->newLog($action, 'teachers', $id, $params);
    }

    public function saveSchoolLog($action, $id = null, $params = null)
    {
        return $this->newLog($action, 'schools', $id, $params);
    }

    public function saveTransactionLog($action, $id = null, $params = null)
    {
        return $this->newLog($action, 'transactions', $id, $params);
    }


}
