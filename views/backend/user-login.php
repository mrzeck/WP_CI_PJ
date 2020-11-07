<form action="" method="post" id="user-form-login" autocomplete="off">
    <div class="row">
        <div class="col-md-6 login-form">
            <?php echo form_open();?>
            <br />
            <br />
            <br />
            <div class="text-center"><?php get_img('logo.png','sikido-admin', ['style' => 'width:150px;']);?></div>

            <div class="login-widget-content">
                <div class="form-group">
                    <span class="icon"><i class="fal fa-user"></i></span>
                    <input name="username" value="<?= set_value('username', '');?>" type="text" class="form-control" placeholder="Tài Khoản" autofocus required autocomplete="off">
                </div>
                <div class="form-group">
                    <span class="icon"><i class="fal fa-lock"></i></span>
                    <input name="password" value="<?= set_value('password', '');?>" type="password" class="form-control" id="password" placeholder="Mật khẩu" required>
                </div>
            </div><!-- /login-widget-content -->

            <div class="login-widget-bottom">
                <div class="col-md-12" style="padding:0">
                    <div class="loader">
                        <div class="dot dot1"></div> <div class="dot dot2"></div> <div class="dot dot3"></div> <div class="dot dot4"></div>
                    </div>
                    <button name="login" value="Đăng nhập" type="submit" class="btn btn-5 btn-block">Đăng Nhập</button>
                </div>
                
                <div class="col-md-12 text-center" style="padding:0">
                    <p>CMS vitechcenter - Phiên bản <?php echo cms_info('version');?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 login-info-company">
            <div class="text-login-info text-center">
                <p style="font-size:20px; font-weight:500;">Cảm ơn bạn đã tin tưởng và lựa chọn <b>vitechcenter</b>!</p>
                <p style="font-size:15px;">Chúng tôi sẽ nỗ lực hết mình để mang đến những trải nghiệm tốt nhất và giúp việc kinh doanh của bạn thành công.</p>
                <p style="font-size:15px;"><b>CSKH:</b>    </p>
                <p style="font-size:15px;"><b>HTKT:</b>     </p>
                <p style="font-size:15px;"><b>Holine:</b>   </p>
            </div>
            <div>
                <img src="https://www.ayhankaraman.com/wp-content/uploads/2017/09/e-ticaret-altyapisi.png" height="450" width="600" class="img-responsive">
            </div>
        </div>
    </div>

    <div class="wcm-loading">
      <div class="wcm-box-bg"></div>
      <div class="wcm-box-thankyou" style="overflow:hidden">
        <div class="thankyou-message-icon unprint">
            <div class="icon icon--order-success svg">
                <svg xmlns="http://www.w3.org/2000/svg" width="72px" height="72px">
                    <g fill="none" stroke="#8EC343" stroke-width="2">
                        <circle cx="36" cy="36" r="35" style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>
                        <path d="M17.417,37.778l9.93,9.909l25.444-25.393" style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>
                    </g>
                </svg>
            </div>
        </div>
      </div>
    </div>
</form>



<style type="text/css" media="screen">
	.loader {
		font-size: 20px;
		position: relative;
		width: 4em;
		height: 1em;
		margin: 0 auto;
		display: none;
	}
	.dot {
		width: 1em;
		height: 1em;
		border-radius: 0.5em;
		background: #fff;
		position: absolute;
		animation-duration: 0.5s;
		animation-timing-function: ease;
		animation-iteration-count: infinite;
	}
	.dot1, .dot2 { left: 0; }
	.dot3 { left: 1.5em; }
	.dot4 { left: 3em; }
	@keyframes reveal { from { transform: scale(0.001); } to { transform: scale(1); } }
	@keyframes slide { to { transform: translateX(1.5em) } }
	.dot1 {  animation-name: reveal; }
	.dot2, .dot3 { animation-name: slide; }
	.dot4 { animation-name: reveal; animation-direction: reverse; /* thx @HugoGiraudel */}

    .login-widget {
        box-shadow: 0 4px 5px 0 rgba(0,0,0,0.14), 0 1px 10px 0 rgba(0,0,0,0.12), 0 2px 4px -1px rgba(0,0,0,0.2);
        max-width: 100%;
        width:1000px!important;
        position: relative;
    }

    #user-form-login {
        overflow:hidden;
    }

    .login-form {
        /* background-color:rgba(255,255,255,0.5); */
        min-height:500px;
    }

    .login-info-company {
        background: #6AC7E8;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        color: white;
        padding: 30px 0;
    }

    .text-login-info {
        padding: 30px 50px 10px 60px;
        text-align: left;
        line-height: 1.8em;
    }
</style>