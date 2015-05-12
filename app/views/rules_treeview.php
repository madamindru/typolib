<div class="treeview">
    <ul>
<?php
    $type_number = 0;
    // Level 1: display all rule types
    foreach ($ruletypes as $id_type => $pretty_name) :
        $type_number++;
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
            foreach ($rules['rules'] as $id_rule => $rule) :
                if ($rule['type'] == $id_type) :
                    $rule_number++;
        ?>
                    <li>
                        <input type="checkbox" id="item-<?=$type_number;?>-<?=$rule_number;?>" />
                        <label for="item-<?=$type_number;?>-<?=$rule_number;?>" class="rule">
                            <?=$rule['content']?>
                        </label>
                        <ul>
                            <?php // Level 3: display all exceptions for each rule ?>
                            <li><a href="./">Exception 1</a></li>
                        </ul>
                    </li>
        <?php
                endif;
            endforeach; // End level 2
        ?>
            </ul>
        </li>
<?php endforeach; // End level 1 ?>
    </ul>
</div>
