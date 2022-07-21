<?php

if (!class_exists('aa_meta_boxes')) {

class aa_meta_boxes
{
    private $metaBoxses = array();
    private $metaBoxesDisabled = array();

    public function __construct($metaBoxes, $metaBoxesDisabled = array())
    {
        $this->metaBoxses = $metaBoxes;
        $this->metaBoxesDisabled = $metaBoxesDisabled;

        add_action('add_meta_boxes', [$this, 'aa_metaBox']);
        add_action('save_post', [$this, 'aa_metaBoxUpdate'], 10, 2);
    }

    public function aa_metaBox()
    {
        $metaBoxes = $this->metaBoxses;
        $metaBoxesDisabled = $this->metaBoxesDisabled;

        $context  = 'side'; // or advanced
        $priority = 'default';
        $params   = array();

        for ($i = 0; $i < count($metaBoxes); $i++) {
            if (!empty($metaBoxes[$i]["params"])) {
                $params = $metaBoxes[$i]["params"];
            }

            add_meta_box(
                $metaBoxes[$i]["id"],
                $metaBoxes[$i]["title"],
                [$this, 'aa_metaBoxCall'],
                $metaBoxes[$i]["screen"],
                $context,
                $priority,
                array(
                    'id'     => $metaBoxes[$i]["id"],
                    'type'   => $metaBoxes[$i]["type"],
                    'title'  => $metaBoxes[$i]["title"],
                    'params' => $params
                ),
            );
        }

        for ($j = 0; $j < count($metaBoxesDisabled); $j++) {
            if (!empty($metaBoxesDisabled[$j]["params"])) {
                $params = $metaBoxesDisabled[$j]["params"];
            }

            add_meta_box(
                $metaBoxesDisabled[$j]["id"],
                $metaBoxesDisabled[$j]["title"],
                [$this, 'aa_metaBoxCall'],
                $metaBoxesDisabled[$j]["screen"],
                $context,
                $priority,
                array(
                    'id'     => $metaBoxesDisabled[$j]["id"],
                    'type'   => $metaBoxesDisabled[$j]["type"],
                    'title'  => $metaBoxesDisabled[$j]["title"],
                    'params' => $params
                ),
            );
        }
    }

    public function aa_metaBoxCall($post, $callback_args)
    {
        wp_enqueue_style('aa-styles', get_template_directory_uri() . '/css/aa-styles.css');

        $id     = $callback_args['args']['id'];
        $type   = $callback_args['args']['type'];
        $title  = $callback_args['args']['title'];
        $params = $callback_args['args']['params'];
        $value  = get_post_meta($post->ID, $id, true);
        
        wp_nonce_field(basename(__FILE__), $id . "_nonce");

        if (!empty($params["disabled"])) { $disabled = $params["disabled"]; }
        else { $disabled = false; }

        switch ($type) {
            case "text":
                ?>
                <label class="screen-reader-text" for="<?php echo $id; ?>"><?php echo $title; ?></label>
                <input class="aa-metaText" type="text" <?php if($disabled) { echo 'disabled="disabled"'; } ?>
                    name="<?php echo $id; ?>" id="<?php echo $id; ?>"
                    value="<?php echo $value; ?>"
                    placeholder="<?php echo $params['placeholder']; ?>" />
                <p><?php echo $params['description']; ?></p>
                <?php
                break;
            case "textarea":
                ?>
                <label class="screen-reader-text" for="<?php echo $id; ?>"><?php echo $title; ?></label>
                <textarea class="aa-metaTextarea" rows="1" cols="40" <?php if($disabled) { echo 'disabled="disabled"'; } ?>
                    name="<?php echo $id; ?>" id="<?php echo $id; ?>"
                    placeholder="<?php echo $params['placeholder']; ?>"><?php echo $value; ?></textarea>
                <p><?php echo $params['description']; ?></p>
                <?php
                break;
            case "url":
                ?>
                <label class="screen-reader-text" for="<?php echo $id; ?>"><?php echo $title; ?></label>
                <input class="aa-metaText" type="url"  <?php if($disabled) { echo 'disabled="disabled"'; } ?>
                    name="<?php echo $id; ?>" id="<?php echo $id; ?>"
                    value="<?php echo $value; ?>"
                    placeholder="<?php echo $params['placeholder']; ?>" />
                <p><?php echo $params['description']; ?></p>
                <?php
                break;
            case "number":
                ?>
                <label class="screen-reader-text" for="<?php echo $id; ?>"><?php echo $title; ?></label>
                <input class="aa-metaNumber" type="number" <?php if($disabled) { echo 'disabled="disabled"'; } ?>
                    name="<?php echo $id; ?>" id="<?php echo $id; ?>"
                    value="<?php if (empty($value)) { echo 0; } else { echo $value; } ?>"
                    min="<?php echo $params['min']; ?>" max="<?php echo $params['max']; ?>"
                    step="<?php echo $params['step']; ?>" />
                <label for="<?php echo $id; ?>"><?php echo $params['label']; ?></label>
                <p><?php echo $params['description']; ?></p>
                <?php
                break;
            case "check":
                $isChecked = "";
                if ($value == "true") { $isChecked = "checked"; }
                ?>
                <label class="screen-reader-text" for="<?php echo $id; ?>"><?php echo $title; ?></label>
                <input class="aa-mateCheck" type="checkbox" <?php if($disabled) { echo 'disabled="disabled"'; } ?>
                    name="<?php echo $id; ?>" id="<?php echo $id; ?>"
                    value="true" <?php echo $isChecked; ?> />
                <label for="<?php echo $id; ?>"><?php echo $params['label']; ?></label>
                <p><?php echo $params['description']; ?></p>
                <?php
                break;
            default:
                echo "";
        }
    }

    public function aa_metaBoxUpdate($post_id, $post)
    {
        $metaBoxes = $this->metaBoxses;

        for ($i = 0; $i < count($metaBoxes); $i++) {
            if (!isset($_POST[$metaBoxes[$i]['id'] . '_nonce']) || !wp_verify_nonce($_POST[$metaBoxes[$i]['id'] . '_nonce'], basename(__FILE__))) {
                return $post_id;
            } elseif (isset($_POST[$metaBoxes[$i]['id']])) {
                $value = $_POST[$metaBoxes[$i]['id']];
            } else {
                $value = '';
            }
            update_post_meta($post_id, $metaBoxes[$i]['id'], $value);
        }
    }
}
# v00.12.11
}