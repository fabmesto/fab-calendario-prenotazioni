<?php if (current_user_can('show_all_prenotazioni') == 1) : ?>
  <!-- Sidebar Menu -->
  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
      <li class="nav-item has-treeview menu-open">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            Gestione
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="<?php echo $this->url('prenotazioni') ?>" class="nav-link <?php echo $this->link_is_active('prenotazioni') ?>">
              <i class="far fa-calendar"></i> Prenotazioni</a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $this->url('users') ?>" class="nav-link <?php echo $this->link_is_active('users') ?>">
              <i class="fa fa-address-card" aria-hidden="true"></i> Utenti</a>
          </li>
          <?php if (current_user_can('edit_users') == 1) : ?>
            <li class="nav-item">
              <a href="<?php echo $this->url('risorse') ?>" class="nav-link <?php echo $this->link_is_active('risorse') ?>">
                <i class="fa fa-th-list" aria-hidden="true"></i> Risorse</a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $this->url('logs') ?>" class="nav-link <?php echo $this->link_is_active('logs') ?>">
                <i class="fa fa-th-list" aria-hidden="true"></i> Logs</a>
            </li>
          <?php endif; ?>
        </ul>
      </li>
    </ul>
  </nav>
<?php endif; ?>