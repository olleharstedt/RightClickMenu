<li class='dropdown-submenu'>
    <a href='<?php echo $questionurls[$question->qid]; ?>'><?php echo $question->title; ?></a>
    <ul class='dropdown-menu'>
        <li class='dropdown-header'><?php echo ellipsize($question->question, true, 50); ?></li>
        <li class='dropdown-header'>
            <?php echo $typeDescriptions[$question->qid]['description']; ?>
        </li>
        <li>
            <a href='<?php echo $questionurls[$question->qid]; ?>'>
                <span class='fa fa-list-alt'></span>&nbsp;Summary
            </a>
        </li>
        <li>
            <a href='<?php echo $editurls[$question->qid]; ?>'>
                <span class='icon-edit'></span>&nbsp;Edit
            </a>
        </li>
        <li>
            <a href='<?php echo $conditionsUrls[$question->qid]; ?>'>
                <span class='icon-conditions'></span>&nbsp;Set conditions
            </a>
        </li>
        <li><a
                href="<?php echo $deleteUrls[$question->qid]; ?>"
                onclick="return confirm('<?php eT("Deleting this question will also delete any answer options and subquestions it includes. Are you sure you want to continue?", "js"); ?>');"
            >
            <span class='fa fa-trash text-warning'></span>&nbsp;Delete</a></li>

        <!-- Subquestions -->
        <?php if (isset($subquestionsUrls[$question->qid])) : ?>
            <li>
                <a href='<?php echo $subquestionsUrls[$question->qid]; ?>'>
                    <span class='icon-defaultanswers'></span>
                    &nbsp;<?php eT('Edit subquestions'); ?>
                </a>
            </li>
        <?php endif; ?>

        <!-- Answer options -->
        <?php if (isset($answerOptionsUrls[$question->qid])) : ?>
            <li>
                <a href='<?php echo $answerOptionsUrls[$question->qid]; ?>'>
                    <span class='icon-defaultanswers'></span>
                    &nbsp;<?php eT('Edit answer options'); ?>
                </a>
            </li>
        <?php endif; ?>

        <!-- Default answers -->
        <?php if (isset($defaultAnswersUrls[$question->qid])) : ?>
            <li>
                <a href='<?php echo $defaultAnswersUrls[$question->qid]; ?>'>
                    <span class='icon-defaultanswers'></span>
                    &nbsp;<?php eT('Edit default answers'); ?>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</li>
