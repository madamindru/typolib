<div class="treeview">
    <ul>
<?php

    $type_number = 0;
    // Level 1: display all rule types
    foreach ($ruletypes as $id_type => $pretty_name) :
?>
        <li>
            <input type="checkbox" id="item-<?=$type_number;?>" />
            <label for="item-<?=$type_number;?>">
                <?=$pretty_name?>
            </label>
            <ul>
        <?php
            $rule_number = 0;
            // Level 2: display all rules for each type
            if (isset($rules['rules'])) :
                foreach ($rules['rules'] as $id_rule => $rule) :
                    if (isset($rule['type']) && $rule['type'] == $id_type) :

        ?>
                        <li>
                            <input type="checkbox" id="item-<?=$type_number;?>-<?=$rule_number;?>" />
                            <label for="item-<?=$type_number;?>-<?=$rule_number;?>" class="rule" data-id-rule="<?=$rule_number;?>">
                                <?=$rule['content']?>
                            </label>
                            <ul class="exceptions">
                                <?php
                                    $exception_number = 0;
                                    // Level 3: display all exceptions for each rule
                                    if (isset($rule_exceptions['exceptions'])) {
                                        foreach ($rule_exceptions['exceptions'] as $id_exception => $exception) {
                                            if (isset($exception['rule_id']) && $exception['rule_id'] == $id_rule) {
                                                $exception = $exception['content'];
                                                include VIEWS . 'view_exception.php';

                                                $exception_number++;
                                            }
                                        } // End level 3
                                    }
                        ?>
                                <a class="new-exception" href="#"><li>New exception…</li></a>
                            </ul>
                        </li>
        <?php
                        $rule_number++;
                    endif;
                endforeach; // End level 2
            endif;
        ?>
            </ul>
        </li>
<?php
    $type_number++;
    endforeach; // End level 1
?>
    </ul>
</div>

<?php include VIEWS . 'view_add_exception.php'; ?>
