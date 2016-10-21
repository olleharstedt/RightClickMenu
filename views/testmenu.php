  <!-- Your custom menu with dropdown-menu as default styling -->
<div id="context-menu">
    <ul class="dropdown-menu" role="menu">
        <li><a tabindex="-1">Action</a></li>
        <li><a tabindex="-1">Another action</a></li>
        <li><a tabindex="-1">Something else here</a></li>
        <li class="divider"></li>

        <?php foreach ($questions as $question): ?>
            <li tabindex="-1">
                <a href='<?php echo $questionurls[$question->qid]; ?>'><?php echo $question->title; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
