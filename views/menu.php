<!-- Your custom menu with dropdown-menu as default styling -->
<div id="context-menu">
    <ul class="dropdown-menu" role="menu">

        <!-- Copy, paste, cut -->
        <li class='dropdown-header'><?php eT('Edit'); ?></li>
        <li>
            <a id='right-click-menu-copy' href="#"><span class='fa fa-clone'></span>&nbsp;<?php eT("Copy");?></a>
        </li>
        <li>
            <a id='right-click-menu-paste' href="#"><span class='fa fa-clipboard'></span>&nbsp;<?php eT("Paste");?></a>
        </li>
        <li>
            <a id='right-click-menu-cut' href="#"><span class='fa fa-scissors'></span>&nbsp;<?php eT("Cut");?></a>
        </li>

        <li class="divider"></li>

        <li class='dropdown-header'>Admin settings</li>
        <li class='dropdown-submenu'>
            <a tabindex="-1">
                <span class="icon-tools" ></span>&nbsp;
                Advanced
            </a>
            <ul class='dropdown-menu'>
                <?php if (Permission::model()->hasGlobalPermission('templates', 'read')) : ?>
                    <!-- Template Editor -->
                    <li class="dropdown-item">
                        <a href="<?php echo $this->createUrl("/admin/templates/sa/view"); ?>">
                            <?php eT("Template editor");?>
                        </a>
                    </li>
                    <?php endif; ?>
                <?php if (Permission::model()->hasGlobalPermission('labelsets', 'read')) : ?>
                    <!-- Edit label sets -->
                    <li class="dropdown-item">
                        <a href="<?php echo $this->createUrl("/admin/labels/sa/view"); ?>">
                            <?php eT("Manage label sets");?>
                        </a>
                    </li>
                    <?php endif;?>

                <!-- Check Data Integrity -->
                <?php if (Permission::model()->hasGlobalPermission('superadmin', 'read')) : ?>

                    <li class="dropdown-item">
                        <a href="<?php echo $this->createUrl("/admin/checkintegrity"); ?>">
                            <?php eT("Check data integrity");?>
                        </a>
                    </li>

                    <!-- Backup Entire Database -->
                    <li class="dropdown-item">
                        <a href="<?php echo $this->createUrl("/admin/dumpdb"); ?>">
                            <?php eT("Backup entire database");?>
                        </a>
                    </li>

                <?php endif;?>

                <!-- Comfort update -->
                <?php if (Permission::model()->hasGlobalPermission('superadmin')) : ?>
                    <li class="dropdown-item">
                        <a href="<?php echo $this->createUrl("/admin/update"); ?>">
                            <?php eT("ComfortUpdate");?>
                        </a>
                    </li>
                <?php endif;?>
            </ul>
        </li>
        <li class='dropdown-submenu'>
            <a tabindex="-1">
                <span class="icon-user" ></span>
                Users
            </a>
            <ul class='dropdown-menu'>
                <!-- Manage survey administrators -->
                <?php if (Permission::model()->hasGlobalPermission('users', 'read')) : ?>
                    <li class="dropdown-item">
                        <a href="<?php echo $this->createUrl("/admin/user/sa/index"); ?>">
                            <?php eT("Manage survey administrators");?>
                        </a>
                    </li>
                    <?php endif;?>
                <?php if (Permission::model()->hasGlobalPermission('usergroups', 'read')) : ?>

                    <!-- Create/edit user groups -->
                    <li class="dropdown-item">
                        <a href="<?php echo $this->createUrl("/admin/usergroups/sa/index"); ?>">
                            <?php eT("Create/edit user groups");?>
                        </a>
                    </li>

                    <?php endif;?>

                <!-- Central participant database -->
                <?php if (Permission::model()->hasGlobalPermission('participantpanel', 'read')) : ?>

                    <li class="dropdown-item">
                        <a href="<?php echo $this->createUrl("/admin/participants/sa/displayParticipants"); ?>">
                            <?php eT("Central participant database"); ?>
                        </a>
                    </li>
                <?php endif;?>
            </ul>
        </li>
        <li class='dropdown-submenu'>
            <a tabindex="-1" class='dropdown-submenu'>
                <span class="icon-global" ></span>
                <?php eT('Settings');?>
            </a>
            <ul class='dropdown-menu'>
                <?php if (Permission::model()->hasGlobalPermission('settings', 'read')) : ?>
                    <!-- Home page settings -->
                    <li class="dropdown-item">
                        <a href="<?php echo $this->createUrl("/admin/homepagesettings"); ?>">
                            <?php eT("Home page settings");?>
                        </a>
                    </li>

                    <!-- Global settings -->
                    <li class="dropdown-item">
                        <a href="<?php echo $this->createUrl("/admin/globalsettings"); ?>">
                            <?php eT("Global settings");?>
                        </a>
                    </li>

                    <li class="dropdown-item">
                        <a href="<?php echo $this->createUrl("/admin/pluginmanager/sa/index"); ?>">
                            <?php eT("Plugin manager");?>
                        </a>
                    </li>
                <?php endif;?>
            </ul>
        </li>
        <li class='dropdown-submenu'>
            <a href="<?php echo $this->createUrl("/admin/survey/sa/listsurveys"); ?>">
                <span class="icon-list" ></span>&nbsp;
                <?php eT("Surveys");?>
            </a>
            <ul class="dropdown-menu" role="menu">
                <?php if (Permission::model()->hasGlobalPermission('surveys', 'create')) : ?>
                     <!-- Create a new survey -->
                     <li>
                         <a href="<?php echo $this->createUrl("/admin/survey/sa/newsurvey"); ?>">
                            <?php eT("Create a new survey");?>
                         </a>
                     </li>

                     <!-- Import a survey -->
                     <li>
                        <a href="<?php echo $this->createUrl("/admin/survey/sa/newsurvey/tab/import"); ?>">
                            <?php eT("Import a survey");?>
                        </a>
                     </li>

                     <!-- Import a survey -->
                     <li>
                        <a href="<?php echo $this->createUrl("/admin/survey/sa/newsurvey/tab/copy"); ?>">
                            <?php eT("Copy a survey");?>
                        </a>
                     </li>

                     <li class="divider"></li>
                    <?php endif;?>
                     <!-- List surveys -->
                 <li>
                     <a href="<?php echo $this->createUrl("/admin/survey/sa/listsurveys"); ?>">
                        <?php eT("List surveys");?>
                     </a>
                 </li>

           </ul>
        </li>

        <?php if ($questionGroups) : ?>
            <li class="divider"></li>
            <li class='dropdown-header'><?php eT('Question explorer'); ?></li>
        <?php endif; ?>

        <?php if ($groupChunks) : ?>
            <?php foreach ($groupChunks as $i => $chunk) : ?>
                <li class='dropdown-submenu'>
                    <a tabindex='-1' href=''><?php eT('Question groups'); ?><?php printf(' %d/%d', $i + 1, count($groupChunks)); ?></a>
                    <ul class='dropdown-menu'>
                        <?php foreach ($chunk as $group) : ?>
                            <?php $plugin->renderPartial(
                                'questionGroup',
                                array(
                                    'groupUrls' => $groupUrls,
                                    'questionurls' => $questionurls,
                                    'editurls' => $editurls,
                                    'conditionsUrls' => $conditionsUrls,
                                    'deleteUrls' => $deleteUrls,
                                    'group' => $group,
                                    'typeDescriptions' => $typeDescriptions
                                )
                            ); ?>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <?php foreach ($questionGroups as $group) : ?>
                <?php $plugin->renderPartial(
                    'questionGroup',
                    array(
                        'groupUrls' => $groupUrls,
                        'questionurls' => $questionurls,
                        'editurls' => $editurls,
                        'conditionsUrls' => $conditionsUrls,
                        'deleteUrls' => $deleteUrls,
                        'group' => $group,
                        'typeDescriptions' => $typeDescriptions
                    )
                ); ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <li class="divider"></li>
        <li class='dropdown-header'>
            <?php echo Yii::app()->session['user']; ?>
        </li>
        <li>
            <a href="<?php echo $this->createUrl("/admin/user/sa/personalsettings"); ?>"><?php eT("Your account");?></a>
        </li>

        <!-- Logout -->
        <li>
            <a href="<?php echo $this->createUrl("/admin/authentication/sa/logout"); ?>">
                <?php eT("Logout");?>
            </a>
        </li>
    </ul>
</div>
