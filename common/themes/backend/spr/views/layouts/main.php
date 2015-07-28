<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <meta name="author" content="Landa - Profesional Website Development" />
        <meta name="application-name" content="Application Default" />
        <link rel="shortcut icon" href="<?php echo bt() ?>/images/favicon.ico" />

        <!-- Mobile Specific Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <?php
        landa()->loginRequired();
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerCssFile(bt() . '/css/main.min.css');
        ?>     

        <script type="text/javascript">
            //adding load class to body and hide page
            document.documentElement.className += 'loadstate';
        </script>
    </head>
    <body>
        <img src="<?php echo bt("images/loaders/horizontal/004.gif") ?>" id="loader" />
        <!-- loading animation -->
        <div id="qLoverlay"></div>
        <div id="qLbar"></div>

        <div id="header">

            <div class="navbar">
                <div class="navbar-inner">
                    <div class="container-fluid">
                        <a class="brand" href="<?php echo url('dashboard') ?>">
                            <?php
                            echo param('clientName');
                            ?>
                        </a>
                        <div class="nav-no-collapse">

                            <ul class="nav">
                                <?php if (user()->isSuperUser) { ?>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <span class="icon16 icomoon-icon-cog"></span> Settings
                                            <b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li class="menu">
                                                <?php
                                                $this->widget('zii.widgets.CMenu', array(
                                                    'items' => array(
                                                        array('label' => '<span class="icon16 iconic-icon-new-window"></span>Site config', 'url' => array('/siteConfig/update', 'id' => param('id'))),
                                                        array('visible' => in_array('inventory', param('menu')) || in_array('accounting', param('menu')), 'label' => '<span class="icon16 minia-icon-office"></span>Unit Kerja', 'url' => array('/departement')),
                                                        array('label' => '<span class="icon16 entypo-icon-users"></span>Access', 'url' => array('/landa/roles')),
                                                    ),
                                                    'encodeLabel' => false,
                                                ));
                                                ?>
                                            </li>
                                        </ul>
                                    </li>
                                <?php } ?>
                            </ul>

                            <ul class="nav pull-right usernav">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle avatar" data-toggle="dropdown">
                                        <?php
                                        $img = user()->avatar_img;
                                        echo '<img src="' . $img['small'] . '" alt="" class="image" /> ';
                                        ?>
                                        <span class="txt"><?php echo Yii::app()->user->getState('name'); ?></span>
                                        <b class="caret"></b>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="menu">
                                            <?php
                                            $user = User::model()->findByPk(user()->id);
                                            $type = 'user';

                                            $this->widget('zii.widgets.CMenu', array(
                                                'items' => array(
                                                    array('label' => '<span class="icon16 icomoon-icon-user-3"></span>Edit profile', 'url' => url('user/updateProfile') . '?type=' . $type),
                                                    array('label' => '<span class="icon16 icomoon-icon-exit"></span> Logout', 'url' => bu().'/site/logout'),
                                                ),
                                                'encodeLabel' => false,
                                            ));
                                            ?>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div><!-- /.nav-collapse -->
                        <!--Responsive navigation button-->  
                        <div class="resBtn">
                            <a href="#"><span class="icon16 minia-icon-list-3"></span></a>
                        </div>
                    </div>
                </div><!-- /navbar-inner -->
            </div><!-- /navbar --> 
        </div><!-- End #header -->

        <div id="wrapper">

            <div id="sidebarbg"></div>
            <!--Sidebar content-->
            <div id="sidebar">
                <div class="sidenav">

                    <div class="sidebar-widget" style="margin: -1px 0 0 0;">
                        <h5 class="title" style="margin-bottom:0">Navigation</h5>
                    </div><!-- End .sidenav-widget -->

                    <div class="mainnav">
                        <?php
                        $this->widget('zii.widgets.CMenu', array(
                            'items' => Auth::model()->modules(),
                            'encodeLabel' => false,
                        ));
                        ?>
                    </div>

                    <div class="sidebar-widget">
                        <h5 class="title">Server Information</h5>
                        <div class="content">
                            <table class="table table-condensed">
                                <tr>
                                    <td><b>Date</b></td>
                                    <td> : </td>
                                    <td><?php echo date('d F Y') ?></td>
                                </tr>
                                <tr>
                                    <td><b>Time</b></td>
                                    <td> : </td>
                                    <td><?php echo date('H:i') ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div><!-- End sidenav -->


            </div><!-- End #sidebar -->



            <!--Body content-->
            <div id="content" class="clearfix">
                <div class="contentwrapper"><!--Content wrapper-->
                    <div class="heading">
                        <h3><?php echo CHtml::encode($this->pageTitle); ?></h3>                    

                    </div><!-- End .heading-->
                    <div class="clearfix"></div>
                    <!-- Build page from here: -->
                    <?php echo $content; ?>
                    <!-- End Build page -->
                </div><!-- End contentwrapper -->
                <div id="footer" class="span12">
                    <?php echo app()->name . ' ' . param('appVersion') ?>  ©  2015 All Rights Reserved. 
                </div>
            </div>
            <!-- End #content -->
        </div><!-- End #wrapper -->
        <a href="#" class="collapseBtn" class="tipR" title="Hide/Show sidebar">
            <div class="landaMin img-polaroid">
                <i class="icon-arrow-left"></i>
            </div>
        </a>
        <?php
        $cs->registerScriptFile(bt() . '/js/main.js', CClientScript::POS_END);
        ?>
    </body>
</html>
