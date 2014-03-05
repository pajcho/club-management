<?php
use App\Pagination\ClubPresenter;

$presenter = new ClubPresenter($paginator);
?>

<div class="row">
    <div class="col-md-5 col-sm-12">
        <div class="pagination pull-left">
            Showing
            <?php echo $paginator->getFrom(); ?>
             to
            <?php echo $paginator->getTo(); ?>
            of
            <?php echo $paginator->getTotal(); ?>
            entries
        </div>
    </div>

    <div class="col-md-7 col-sm-12">
        <ul class="pagination pull-right">
            <?php echo $presenter->render(); ?>
        </ul>
    </div>
</div>

