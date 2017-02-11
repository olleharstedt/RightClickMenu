<li class='dropdown-submenu'>
    <a tabindex='-1' href='<?php echo $groupUrls[$group->gid]; ?>'><?php echo $group->group_name; ?></a>
    <ul class='dropdown-menu'>
        <?php foreach ($group->questions as $question) : ?>
            <?php $plugin->renderPartial(
                    'question',
                    array(
                        'questionurls' => $questionurls,
                        'editurls' => $editurls,
                        'conditionsUrls' => $conditionsUrls,
                        'deleteUrls' => $deleteUrls,
                        'typeDescriptions' => $typeDescriptions,
                        'question' => $question
                    )
                );
            ?>
        <?php endforeach; ?>
    </ul>
</li>
