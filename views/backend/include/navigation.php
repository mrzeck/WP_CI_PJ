<div id="adminmenumain" role="navigation" aria-label="Trình đơn chính">
    <div class="header-logo">
        <a href=""><img src="<?= $this->template->get_assets();?>images/logo.png" alt=""></a>
        <a href="" class="pull-right" target="_black" style="padding-top:5px;color:#4CB6FF;"><i class="fad fa-house-damage"></i></a>
    </div>
    <div id="adminmenuback"></div>
    <div id="adminmenuwrap">
        <ul id="adminmenu">
            <?php admin_nav($group, $active);?>
        </ul>
    </div>
    <div class="nav-user">
        <div class="account-info">
            <a href="javascript:void(0)" title="Cấu hình" style="padding: 10px 10px 10px 20px;" data-toggle="collapse" data-target="#list-action-user" id="show-action-user">
                <img class="profile-pic img-circle" src="<?= (!empty($user->avatar))?SOURCE.$user->avatar:$this->template->get_assets().'images/avatar.png';?>">
                <span style="margin:0 5px"><?php echo $user->firstname.' '.$user->lastname;?></span>
                <span id="caret-menu-user"><i class="fal fa-angle-up caret-menu-icon"></i></span>
            </a>

            <div id="list-action-user" class="collapse">
                <ul class="nav-user-sub">
                    <li><a href="<?php echo admin_url('user/edit?view=profile') ;?>"><i class="fal fa-user"></i> <span>Tài khoản của bạn</span></a></li>
                    <li><a href="<?php echo admin_url('user/edit?view=password') ;?>"><i class="fal fa-unlock"></i> <span>Đổi mật khẩu</span></a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo admin_url('user/logout') ;?>"><i class="fal fa-sign-out-alt"></i> <span>Đăng xuất</span></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

