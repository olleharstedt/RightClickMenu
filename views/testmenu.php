  <!-- Your custom menu with dropdown-menu as default styling -->
<div id="context-menu">
    <ul class="dropdown-menu" role="menu">
        <li><a tabindex="-1">Action</a></li>
        <li><a tabindex="-1">Another action</a></li>
        <li class="divider"></li>
        <li class='dropdown-header'>Question groups</li>

        <?php foreach ($questionGroups as $group): ?>
            <li class='dropdown-submenu'>
                <a tabindex='-1'><?php echo $group->group_name; ?></a>
                <ul class='dropdown-menu'>
                    <?php foreach ($group->questions as $question): ?>
                        <li class='dropdown-submenu'>
                            <a href='<?php echo $questionurls[$question->qid]; ?>'><?php echo $question->title; ?></a>
                            <ul class='dropdown-menu'>
                                <li class='dropdown-header'><?php echo ellipsize($question->question, true, 30); ?></li>
                                <li><a><span class='fa fa-list-alt'></span>&nbsp;Summary</a></li>
                                <li><a><span class='icon-edit'></span>&nbsp;Edit</a></li>
                                <li><a><span class='icon-conditions'></span>&nbsp;Set conditions</a></li>
                                <li><a><span class='fa fa-trash text-warning'></span>&nbsp;Delete</a></li>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
