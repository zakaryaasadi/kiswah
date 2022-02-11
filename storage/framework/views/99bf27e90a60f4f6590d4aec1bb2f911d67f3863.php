<!DOCTYPE html>
<html lang="en">
<head>
   <?php echo $__env->make('partial.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/plugins.css')); ?>">
    <link href="<?php echo e(asset('css/styles.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/extra.css')); ?>" rel="stylesheet">
    <?php echo $__env->yieldContent('links'); ?>
</head>
<body>
<div class="main-wrapper">
   <?php echo $__env->yieldContent('content'); ?>


</div>
<?php echo $__env->yieldContent('script'); ?>
</body>
</html>



<?php /**PATH /var/www/kiswah/resources/views/layouts/master.blade.php ENDPATH**/ ?>