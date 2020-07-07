<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><?php echo ucwords($this->name) ?> <small><?php echo ucwords($this->parent->current_action) ?></small></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="<?php echo $this->url_home() ?>">
                            <i class="fa fa-dashboard"></i>
                            <?php echo ucwords($this->name) ?>
                        </a>
                    </li>
                    <li class="breadcrumb-item active"><?php echo ucwords($this->parent->current_action) ?></a></li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <p>Modifica non permessa</p>
    </div>
</section>