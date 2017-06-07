<?php
add_theme_support('menus');
// Metaboxes
//include_once(get_template_directory() . '/framework/metaboxes.php');

// Extend Visual Composer
get_template_part('shortcodes');

// Custom Functions
get_template_part('framework/custom_functions');

// Plugins
//include_once(get_template_directory() . '/framework/plugins/multiple_sidebars.php');

// Widgets
get_template_part('widgets/widgets');

/* Включил миниатюры */
if (function_exists('add_theme_support'))
    {
    add_theme_support('post-thumbnails');
    add_image_size('loopThumb', 77, 77, true);
    add_image_size('reviewThumb', 86, 86, true);
    add_image_size('blogThumb', 600, 307, true);
    add_image_size('portfolioThumb', 1000, 600, true);
    add_image_size('blogbigThumb', 1140, 584, true);
    }

function pre($array) {
    echo('<pre>');
    print_r($array);
    echo('</pre>');
}

/* Хлебные крошки */

function the_breadcrumb() {
    echo '<div class="mt-breadcrumbs"><div class="container"><div itemprop="breadcrumb" class="breadcrumb-trail breadcrumbs"><span class="trail-begin"><a href="/">Home</a></span><span class="sep"><i class="fa fa-caret-right"></i></span>';

    if (is_category() || is_single())
        {
        $cats = get_the_category();
        $cat = $cats[0];
        echo '<span class="trail-begin"><a href="' . get_category_link($cat->term_id) . '">' . $cat->name . '</a></span><span class="sep"><i class="fa fa-caret-right"></i></span>';
        }

    if (is_single())
        {
        echo '<span class="trail-end">';
        the_title();
        echo '</span>';
        }

    if (is_page())
        {
        echo '<span class="trail-end">';
        the_title();
        echo '</span>';
        }

    echo '</div><div class="clear"></div></div></div>';
}

//create widget for skills text
class Skills_Text_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
                'skills_text', 'Skills text', array('description' => __('Add text to skills', 'text_domain'),)
        );
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['text'] = stripslashes(wp_filter_post_kses(addslashes($new_instance['text']))); // wp_filter_post_kses() expects slashed
        $instance['filter'] = isset($new_instance['filter']);
        return $instance;
    }

    public function form($instance) {
        $instance = wp_parse_args((array) $instance, array('text' => ''));
        $text = esc_textarea($instance['text']);
        ?>
        <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text');?>" name="<?php echo $this->get_field_name('text');?>"><?php echo $text;?></textarea>
        <p><input id="<?php echo $this->get_field_id('filter');?>" name="<?php echo $this->get_field_name('filter');?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0);?> />&nbsp;<label for="<?php echo $this->get_field_id('filter');?>"><?php _e('Automatically add paragraphs');?></label></p>
        <?php
    }

    public function widget($args, $instance) {
        $text = apply_filters('widget_text', empty($instance['text']) ? '' : $instance['text'], $instance);
        echo $args['before_widget'];
        if (!empty($title))
            {
            echo $args['before_title'] . $title . $args['after_title'];
            }
        ?>
        <div class="textwidget"><?php echo!empty($instance['filter']) ? wpautop($text) : $text;?></div>
        <?php
        echo $args['after_widget'];
    }

}

add_action('widgets_init', function() {
    register_widget('Skills_Text_Widget');
});
//end create widget for skills text
/* регистрируем левый сайдбар для страницы BLOG */

if (function_exists('register_sidebar'))
    {

    /* В боковой колонке */
    register_sidebar(
            array(
        'id' => 'sidebar_for_blog', // уникальный id для отображения в виджетах
        'name' => 'Боковая колонка в Blog', // название сайдбара
        'description' => 'Перетащите сюда виджеты, чтобы добавить их в сайдбар.', // описание
        'before_widget' => '<div id="%1$s" class="widget %2$s">', // по умолчанию виджеты выводятся <li>-списком
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">', // по умолчанию заголовки виджетов в <h2>
        'after_title' => '</h4>'
            ), register_sidebar(
                    array(
                        'id' => 'text_for_skills', // уникальный id для отображения в виджетах
                        'name' => 'Text for skills', // название сайдбара
                        'description' => 'Move widgets to add it to sidebar.', // описание
                        'before_widget' => '<div class="lh20">', // по умолчанию виджеты выводятся <li>-списком
                        'after_widget' => '</div>',
                        'before_title' => '', // по умолчанию заголовки виджетов в <h2>
                        'after_title' => ''
                    )
            )
    );
    }

//множественные фото для портфолио
if (class_exists('MultiPostThumbnails'))
    {

    new MultiPostThumbnails(array(
        'label' => 'Secondary Image',
        'id' => 'secondary-image',
        'post_type' => 'post'
    ));
    new MultiPostThumbnails(array(
        'label' => 'Thrid Image',
        'id' => 'thrid-image',
        'post_type' => 'post'
    ));
    new MultiPostThumbnails(array(
        'label' => 'Four Image',
        'id' => 'four-image',
        'post_type' => 'post'
    ));
    }
/**
 * Определим константу, которая будет хранить путь к папке single
 */
define(SINGLE_PATH, TEMPLATEPATH . '/single');

/**
 * Добавим фильтр, который будет запускать функцию подбора шаблонов
 */
add_filter('single_template', 'my_single_template');

/**
 * Функция для подбора шаблона
 */
function my_single_template($single) {
    // $catSlug = array("analysis", "branding", "development", "promotion");
    global $wp_query, $post;

    /**
     * Проверяем наличие шаблонов по ID поста.
     * Формат имени файла: single-ID.php
     */
    /* if(file_exists(SINGLE_PATH . '/single-' . $post->ID . '.php')) {
      return SINGLE_PATH . '/single-' . $post->ID . '.php';
      } */

    /**
     * Проверяем наличие шаблонов для категорий, ищем по ID категории или слагу
     * Формат имени файла: single-cat-SLUG.php или single-cat-ID.php
     */
    foreach ((array) get_the_category() as $cat) :

        if (file_exists(SINGLE_PATH . '/single-cat-' . $cat->slug . '.php'))
            return SINGLE_PATH . '/single-cat-' . $cat->slug . '.php';

      /*   elseif (in_array($cat->slug, $catSlug))
            return SINGLE_PATH . '/single-cat-analysis.php'; */
        /* elseif(file_exists(SINGLE_PATH . '/single-cat-' . $cat->term_id . '.php'))
          return SINGLE_PATH . '/single-cat-' . $cat->term_id . '.php'; */

    endforeach;

    /**
     * Проверяем наличие шаблонов для тэгов, ищем по ID тэга или слагу
     * Формат имени файла: single-tag-SLUG.php или single-tag-ID.php
     */
    /* $wp_query->in_the_loop = true;
      foreach((array)get_the_tags() as $tag) :

      if(file_exists(SINGLE_PATH . '/single-tag-' . $tag->slug . '.php'))
      return SINGLE_PATH . '/single-tag-' . $tag->slug . '.php';

      elseif(file_exists(SINGLE_PATH . '/single-tag-' . $tag->term_id . '.php'))
      return SINGLE_PATH . '/single-tag-' . $tag->term_id . '.php';

      endforeach;
      $wp_query->in_the_loop = false; */

    /**
     * Если ничего не найдено открываем стандартный single.php
     */
    if (file_exists(SINGLE_PATH . '/single.php'))
        {
        return SINGLE_PATH . '/single.php';
        }
    return $single;
}

// Register custom post types
add_action('init', 'pyre_init');

function pyre_init() {
    global $data;
    register_post_type(
            'rwt-portfolio', array(
        'labels' => array(
            'name' => 'Portfolio',
            'singular_name' => 'Portfolio'
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-portfolio',
        'rewrite' => array('slug' => "portfolio-items"),
        'supports' => array('title', 'editor', 'thumbnail', 'comments'),
        'can_export' => true,
            )
    );

    register_taxonomy('portfolio-category', 'rwt-portfolio', array('hierarchical' => true, 'label' => 'Categories', 'query_var' => true, 'rewrite' => true));
    register_taxonomy('portfolio_skills', 'rwt-portfolio', array('hierarchical' => true, 'label' => 'Skills', 'query_var' => true, 'rewrite' => true));
}

//filter rwt-portfolio add
function restrict_rwt_portfolio() {
    global $typenow;
    $post_type = 'rwt-portfolio'; // change HERE
    $taxonomy = 'portfolio_category'; // change HERE
    if ($typenow == $post_type)
        {
        $selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
        $info_taxonomy = get_taxonomy($taxonomy);
        wp_dropdown_categories(array(
            'show_option_all' => __("Show All {$info_taxonomy->label}"),
            'taxonomy' => $taxonomy,
            'name' => $taxonomy,
            'orderby' => 'name',
            'selected' => $selected,
            'show_count' => true,
            'hide_empty' => true,
        ));
        };
}

add_action('restrict_manage_posts', 'restrict_rwt_portfolio');

function convert_id_to_term_rwt_portfolio($query) {
    global $pagenow;
    $post_type = 'rwt-portfolio'; // change HERE
    $taxonomy = 'portfolio_category'; // change HERE
    $q_vars = &$query->query_vars;
    if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0)
        {
        $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
        $q_vars[$taxonomy] = $term->slug;
        }
}

add_filter('parse_query', 'convert_id_to_term_rwt_portfolio');

//filter rwt-portfolio add end
////////
// подключаем функцию активации мета блока (my_extra_fields)
add_action('add_meta_boxes', 'my_extra_fields', 1);

function my_extra_fields() {
    add_meta_box('extra_fields', 'Additional fields', 'extra_fields_box_func', 'rwt-portfolio', 'normal', 'high');
}

// код блока
function extra_fields_box_func($post) {
    ?>
    <p><label><span style="width:10%;float:left;padding-top: 5px">Demo site name</span> <input type="text" name="extra[name]" value="<?php echo get_post_meta($post->ID, 'name', 1);?>" style="width:80%" /></label></p>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
    <p><label><span style="width:10%;float:left;padding-top: 5px">Demo site URL</span> <input type="text" name="extra[url]" value="<?php echo get_post_meta($post->ID, 'url', 1);?>" style="width:80%" /></label></p>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
     <p><label><span style="width:10%;float:left;padding-top: 5px">Description Title</span> <input type="text" placeholder="Priject description" name="extra[destitle]" value="<?php echo get_post_meta($post->ID, 'destitle', 1);?>" style="width:80%" /></label></p>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
    <?php
}

add_action('add_meta_boxes', 'my_extra_fields_page', 1);

function my_extra_fields_page() {
    add_meta_box('extra_fields', 'Portfolio "category slug" for slider and prices(Product pages)', 'extra_fields_box_func_page', 'page', 'normal', 'high');
}

// код блока
function extra_fields_box_func_page($post) {
    ?>
    <p><label><span style="width:12%;float:left;padding-top: 5px">Short description</span> <input type="text" name="extra[description]" value="<?php echo get_post_meta($post->ID, 'description', 1);?>" style="width:80%" /></label></p> 
	
	    <p><label><span style="width:12%;float:left;padding-top: 5px">First option</span> <input placeholder="Option Name" type="text" name="extra[option_name1]" value="<?php echo get_post_meta($post->ID, 'option_name1', 1);?>" style="width:38%; margin-right:4%;" /><input placeholder="Option Value" type="text" name="extra[option_value1]" value="<?php echo get_post_meta($post->ID, 'option_value1', 1);?>" style="width:38%" /></label></p> 
		
	    <p><label><span style="width:12%;float:left;padding-top: 5px">Second option</span> <input placeholder="Option Name" type="text" name="extra[option_name2]" value="<?php echo get_post_meta($post->ID, 'option_name2', 1);?>" style="width:38%; margin-right:4%;" /><input placeholder="Option Value" type="text" name="extra[option_value2]" value="<?php echo get_post_meta($post->ID, 'option_value2', 1);?>" style="width:38%" /></label></p> 
		
	    <p><label><span style="width:12%;float:left;padding-top: 5px">Third option</span> <input placeholder="Option Name" type="text" name="extra[option_name3]" value="<?php echo get_post_meta($post->ID, 'option_name3', 1);?>" style="width:38%; margin-right:4%;" /><input placeholder="Option Value" type="text" name="extra[option_value3]" value="<?php echo get_post_meta($post->ID, 'option_value3', 1);?>" style="width:38%" /></label></p> 
		 
		<p><label><span style="width:12%;float:left;padding-top: 5px">Right column</span></label> 
		<?php 	$content = get_post_meta($post->ID, 'right1', 1);
						$editor_id = 'right1'; 
						$settings = array('wpautop' => true, 'media_buttons' => true, 'tinymce' => true );?>
                <?php wp_editor( $content, $editor_id, $settings ); ?>
		</p>		
					
	
	<p><label><span style="width:12%;float:left;padding-top: 5px">Category slug for slider</span> <input type="text" name="extra[name]" value="<?php echo get_post_meta($post->ID, 'name', 1);?>" style="width:80%" /></label></p>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" /><br/>
    <p><label><span style="width:12%;float:left;padding-top: 5px">Category slug for prices</span> <input type="text" name="extra[price]" value="<?php echo get_post_meta($post->ID, 'price', 1);?>" style="width:80%" /></label></p>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" /><br/><br/>
    <p><label><span style="width:12%;float:left;padding-top: 5px">Demo site(url)</span> <input type="text" name="extra[demo]" value="<?php echo get_post_meta($post->ID, 'demo', 1);?>" style="width:80%" /></label></p>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
    
    <p><label><span style="width:15%;float:left;padding-top: 5px">Prices</span> <input type="text" name="extra[prices]" value="<?php echo get_post_meta($post->ID, 'prices', 1);?>" style="width:80%" /></label></p>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
    <p><label><span style="width:15%;float:left;padding-top: 5px">Portfolio</span> <input type="text" name="extra[portfolio]" value="<?php echo get_post_meta($post->ID, 'portfolio', 1);?>" style="width:80%" /></label></p>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
    <p><label><span style="width:15%;float:left;padding-top: 5px">Product Guide</span> <input type="text" name="extra[guide]" value="<?php echo get_post_meta($post->ID, 'guide', 1);?>" style="width:80%" /></label></p>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" /><br/>
    <p><label><span style="width:15%;float:left;padding-top: 5px">Request Information/Demo</span> <input type="text" name="extra[reqinf]" value="<?php echo get_post_meta($post->ID, 'reqinf', 1);?>" style="width:80%" /></label></p>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />

    <?php
}

// включаем обновление полей при сохранении
add_action('save_post', 'my_extra_fields_update', 0);

/* Сохраняем данные, при сохранении поста */

function my_extra_fields_update($post_id) {
    if (!wp_verify_nonce($_POST['extra_fields_nonce'], __FILE__))
        return false; // проверка
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return false; // если это автосохранение
    if (!current_user_can('edit_post', $post_id))
        return false; // если юзер не имеет право редактировать запись

    if (!isset($_POST['extra']))
        return false;

    // Все ОК! Теперь, нужно сохранить/удалить данные
    $_POST['extra'] = array_map('trim', $_POST['extra']);
    foreach ($_POST['extra'] as $key => $value) {
        if (empty($value))
            {
            delete_post_meta($post_id, $key); // удаляем поле если значение пустое
            continue;
            }

        update_post_meta($post_id, $key, $value); // add_post_meta() работает автоматически
    }
	if (isset($_POST['right1'])){
		$key = 'right1';
		$value = $_POST['right1'];
		update_post_meta($post_id, $key, $value); // add_post_meta() работает автоматически
	}	
	if (isset($_POST['text1'])){
		$key = 'text1';
		$value = $_POST['text1'];
		update_post_meta($post_id, $key, $value); // add_post_meta() работает автоматически
	}	
	if (isset($_POST['text2'])){
		$key = 'text2';
		$value = $_POST['text2'];
		update_post_meta($post_id, $key, $value); // add_post_meta() работает автоматически
	}	
	if (isset($_POST['text3'])){
		$key = 'text3';
		$value = $_POST['text3'];
		update_post_meta($post_id, $key, $value); // add_post_meta() работает автоматически
	}
    return $post_id;
}

// Register traning
add_action('init', 'training_init');

function training_init() {
    global $data;
    register_post_type(
            'training', array(
        'labels' => array(
            'name' => 'Training',
            'singular_name' => 'Training'
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-welcome-learn-more',
        'rewrite' => array('slug' => $data['traning_slug']),
        'supports' => array('title', 'editor', 'thumbnail', 'comments', 'excerpt'),
		'taxonomies' => array('post_tag'),
        'can_export' => true,
            )
    );

    register_taxonomy('training_categories', 'training', array('hierarchical' => true, 'label' => 'Training categories', 'query_var' => true, 'rewrite' => true));
}
// Register RWTVideo
add_action('init', 'rwtvideo_init');

function rwtvideo_init() {
    global $data;
    register_post_type(
            'rwtvideo', array(
        'labels' => array(
            'name' => 'RWT Videos',
            'singular_name' => 'RWT Video'
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-format-video',
        'rewrite' => array('slug' => $data['rwtvideo_slug']),
        'supports' => array('title', 'editor', 'thumbnail', 'comments', 'excerpt'),
		'taxonomies' => array('post_tag'),
        'can_export' => true,
            )
    );

    register_taxonomy('rwtvideo_categories', 'rwtvideo', array('hierarchical' => true, 'label' => 'RWT Videos categories', 'query_var' => true, 'rewrite' => true));
}

// Register prices
add_action('init', 'prices_init');

function prices_init() {
    global $data;
    register_post_type(
            'prices', array(
        'labels' => array(
            'name' => 'Prices',
            'singular_name' => 'Prices'
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-tag',
        'rewrite' => array('slug' => $data['prices_slug']),
        'supports' => array('title'),
        'can_export' => true,
            )
    );
}

// подключаем функцию активации мета блока (my_extra_fields_for_prices)
add_action('add_meta_boxes', 'my_extra_fields_for_prices', 1);

function my_extra_fields_for_prices() {
    add_meta_box('extra_fields', 'Form for fields', 'extra_fields_for_prices_func', 'prices', 'normal', 'high');
}

// код блока
function extra_fields_for_prices_func($post) {
    ?><table style="width: 100%;">
        <tr>
            <th></th>
            <th>First column</th>
            <th>Second column</th>
            <th>Third column</th>
        </tr>
        <tr>
            <td>Name of column</td>
            <td>
                <label><input type="text" name="extra[name1]" value="<?php echo get_post_meta($post->ID, 'name1', 1);?>" style="width: 100%;" /></label>
                <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
            </td>
            <td>
                <label> <input type="text" name="extra[name2]" value="<?php echo get_post_meta($post->ID, 'name2', 1);?>" style="width: 100%;" /></label>
                <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
            </td>
            <td>
                <label> <input type="text" name="extra[name3]" value="<?php echo get_post_meta($post->ID, 'name3', 1);?>" style="width: 100%;" /></label>
                <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
            </td>
        </tr>
       <tr>
            <td>Actual price</td>
            <td>
                <label> <input type="text" name="extra[price1]" value="<?php echo get_post_meta($post->ID, 'price1', 1);?>" style="width: 100%;" /></label>
                <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
            </td>
            <td>
                <label> <input type="text" name="extra[price2]" value="<?php echo get_post_meta($post->ID, 'price2', 1);?>" style="width: 100%;" /></label>
                <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
            </td>
            <td>
                <label> <input type="text" name="extra[price3]" value="<?php echo get_post_meta($post->ID, 'price3', 1);?>" style="width: 100%;" /></label>
                <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
            </td>
        </tr>
         <!--<tr>
            <td>Product guide url</td>
            <td>
                <label><input type="text" name="extra[guide1]" value="<?php echo get_post_meta($post->ID, 'guide1', 1);?>" style="width: 100%;" /></label>
                <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
            </td>
            <td>
                <label> <input type="text" name="extra[guide2]" value="<?php echo get_post_meta($post->ID, 'guide2', 1);?>" style="width: 100%;" /></label>
                <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
            </td>
            <td>
                <label> <input type="text" name="extra[guide3]" value="<?php echo get_post_meta($post->ID, 'guide3', 1);?>" style="width: 100%;" /></label>
                <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
            </td>
        </tr>
        <tr>
            <td>Request info url</td>
            <td>
                <label> <input type="text" name="extra[rinfo1]" value="<?php echo get_post_meta($post->ID, 'rinfo1', 1);?>" style="width: 100%;" /></label>
                <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
            </td>
            <td>
                <label><input type="text" name="extra[rinfo2]" value="<?php echo get_post_meta($post->ID, 'rinfo2', 1);?>" style="width: 100%;" /></label>
                <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
            </td>
            <td>
                <label><input type="text" name="extra[rinfo3]" value="<?php echo get_post_meta($post->ID, 'rinfo3', 1);?>" style="width: 100%;" /></label>
                <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
            </td>
        </tr>--> 
        <tr>
            <td>Content boxes</td>
            <td>
				<?php 	$content = get_post_meta($post->ID, 'text1', 1);
						$editor_id = 'text1'; 
						$settings = array('wpautop' => true, 'media_buttons' => false, 'tinymce' => true );?>
                <?php wp_editor( $content, $editor_id, $settings ); ?>
            </td>
            <td>
               <?php 	$content = get_post_meta($post->ID, 'text2', 1);
						$editor_id = 'text2'; 
						$settings = array('wpautop' => true, 'media_buttons' => false, 'tinymce' => true );?>
                <?php wp_editor( $content, $editor_id, $settings ); ?>
            </td>
            <td>
                <?php 	$content = get_post_meta($post->ID, 'text3', 1);
						$editor_id = 'text3'; 
						$settings = array('wpautop' => true, 'media_buttons' => false, 'tinymce' => true );?>
				<?php wp_editor( $content, $editor_id, $settings ); ?>
            </td>
        </tr>
    </table>
    <h2>For price page</h2>
    <h4>Portfolio page (enter url of portfolio)</h4>
    <label><input type="text" name="extra[portfolio_page]" value="<?php echo get_post_meta($post->ID, 'portfolio_page', 1);?>" style="width: 100%;" /></label>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
    <h4>Product Page (enter url of product page)</h4>
    <label><input type="text" name="extra[product_page]" value="<?php echo get_post_meta($post->ID, 'product_page', 1);?>" style="width: 100%;" /></label>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
    <?php
}

// Register testimonials
add_action('init', 'testimonials_init');

function testimonials_init() {
    global $data;
    register_post_type(
            'testimonials', array(
        'labels' => array(
            'name' => 'Testimonials',
            'singular_name' => 'Testimonials'
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-format-quote',
        'rewrite' => array('slug' => 'testimonial'),
        'supports' => array('title', 'editor', 'thumbnail', 'comments', 'excerpt'),
        'can_export' => true,
            )
    );

    register_taxonomy('testimonials_categories', 'testimonials', array('hierarchical' => true, 'label' => 'Testimonial categories', 'query_var' => true, 'rewrite' => true));
}

// подключаем функцию активации мета блока (my_extra_fields_for_testimonials)
add_action('add_meta_boxes', 'my_extra_fields_for_testimonials', 1);

function my_extra_fields_for_testimonials() {
    add_meta_box('extra_fields', 'Additional fields', 'extra_fields_for_testimonials_func', 'testimonials', 'normal', 'high');
}

// код блока
function extra_fields_for_testimonials_func($post) {
    ?>
    <p><label><span style="width:15%;float:left;padding-top: 5px">Prices</span> <input type="text" name="extra[prices]" value="<?php echo get_post_meta($post->ID, 'prices', 1);?>" style="width:80%" /></label></p>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
    <p><label><span style="width:15%;float:left;padding-top: 5px">Portfolio</span> <input type="text" name="extra[portfolio]" value="<?php echo get_post_meta($post->ID, 'portfolio', 1);?>" style="width:80%" /></label></p>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
    <?php
}

// Register main_page_content
add_action('init', 'main_page_content');

function main_page_content() {
    global $data;
    register_post_type(
            'mpagecont', array(
        'labels' => array(
            'name' => 'Main Page Content',
            'singular_name' => 'Main Page Content'
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-welcome-view-site',
        'rewrite' => array('slug' => $data['mpagecont_slug']),
        'supports' => array('title', 'editor', 'thumbnail', 'comments', 'excerpt'),
        'can_export' => true,
            )
    );
}

// Register Web_design_examples
add_action('init', 'web_design_examples');

function web_design_examples() {
    global $data;
    register_post_type(
            'webdesignexamples', array(
        'labels' => array(
            'name' => 'Web Design Examples',
            'singular_name' => 'Web Design Examples'
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-welcome-view-site',
        'supports' => array('title', 'thumbnail'),
        'can_export' => true,
            )
    );
}

// Register Recruitment website template
add_action('init', 'website_themplates');

function website_themplates() {
    global $data;
    register_post_type(
            'websitethemplates', array(
        'labels' => array(
            'name' => 'Website templates',
            'singular_name' => 'Website template'
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-welcome-view-site',
        'supports' => array('title', 'custom-fields', 'thumbnail'),
        'can_export' => true,
            )
    );
}

// подключаем функцию активации мета блока (my_extra_fields_for_main)
add_action('add_meta_boxes', 'my_extra_fields_for_main', 1);

function my_extra_fields_for_main() {
    add_meta_box('extra_fields', 'Additional fields', 'extra_fields_for_main_func', 'mpagecont', 'normal', 'high');
}

// код блока
function extra_fields_for_main_func($post) {
    ?>
    <p><label><span style="width:15%;float:left;padding-top: 5px">Read More</span> <input type="text" name="extra[rmore]" value="<?php echo get_post_meta($post->ID, 'rmore', 1);?>" style="width:80%" /></label></p>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
    <p><label><span style="width:15%;float:left;padding-top: 5px">Prices</span> <input type="text" name="extra[prices]" value="<?php echo get_post_meta($post->ID, 'prices', 1);?>" style="width:80%" /></label></p>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
    <p><label><span style="width:15%;float:left;padding-top: 5px">Portfolio</span> <input type="text" name="extra[portfolio]" value="<?php echo get_post_meta($post->ID, 'portfolio', 1);?>" style="width:80%" /></label></p>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
    <p><label><span style="width:15%;float:left;padding-top: 5px">Product Guide</span> <input type="text" name="extra[guide]" value="<?php echo get_post_meta($post->ID, 'guide', 1);?>" style="width:80%" /></label></p>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" /><br/>
    <p><label><span style="width:15%;float:left;padding-top: 5px">Request Information/Demo</span> <input type="text" name="extra[reqinf]" value="<?php echo get_post_meta($post->ID, 'reqinf', 1);?>" style="width:80%" /></label></p>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" /><br/>
    <p><label><span style="width:15%;float:left;padding-top: 5px">ID of YouTube video</span> <input type="text" name="extra[video_link]" value="<?php echo get_post_meta($post->ID, 'video_link', 1);?>" style="width:80%" /></label></p>
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__);?>" />
    <?php
}

// Add post thumbnail functionality
add_theme_support('post-thumbnails');
add_image_size('blog-large', 669, 272, true);
add_image_size('blog-medium', 320, 202, true);
add_image_size('tabs-img', 52, 50, true);
add_image_size('related-img', 180, 138, true);
add_image_size('portfolio-one', 540, 272, true);
add_image_size('portfolio-two', 480, 295, true);
add_image_size('portfolio-three', 370, 210);
add_image_size('portfolio-slider', 286, 180, true);
add_image_size('portfolio-four', 220, 161, true);
add_image_size('portfolio-full', 940, 400, true);
add_image_size('recent-posts', 700, 441, true);
add_image_size('recent-works-thumbnail', 66, 66, true);
//множественные фото для портфолио
if (class_exists('MultiPostThumbnails'))
    {

    new MultiPostThumbnails(array(
        'label' => 'Secondary Image',
        'id' => 'secondary-image',
        'post_type' => 'rwt-portfolio'
    ));
    new MultiPostThumbnails(array(
        'label' => 'Thrid Image',
        'id' => 'thrid-image',
        'post_type' => 'rwt-portfolio'
    ));
    new MultiPostThumbnails(array(
        'label' => 'Four Image',
        'id' => 'four-image',
        'post_type' => 'rwt-portfolio'
    ));
    }

// numbered pagination
function pagination($pages = '', $range = 4) {
    $showitems = ($range * 2) + 1;

    global $paged;
    if (empty($paged))
        $paged = 1;

    if ($pages == '')
        {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if (!$pages)
            {
            $pages = 1;
            }
        }

    if (1 != $pages)
        {
        echo "<div class=\"pagination\">";
        /*     if ($paged > 2 && $paged > $range + 1 && $showitems < $pages)
          echo "<a href='" . get_pagenum_link(1) . "'>&laquo; First</a>"; */
        if ($paged > 1 && $showitems < $pages)
            echo "<a href='" . get_pagenum_link($paged - 1) . "'>←</a>";

        for ($i = 1; $i <= $pages; $i++) {
            if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems ))
                {
                echo ($paged == $i) ? "<span class=\"current\">" . $i . "</span>" : "<a href='" . get_pagenum_link($i) . "' class=\"inactive\">" . $i . "</a>";
                }
        }

        if ($paged < $pages && $showitems < $pages)
            echo "<a href=\"" . get_pagenum_link($paged + 1) . "\">→</a>";
        /*    if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages)
          echo "<a href='" . get_pagenum_link($pages) . "'>Last →</a>"; */
        echo "</div>\n";
        }
}

/* регистрирую файл настроек темы */

// create custom plugin settings menu
add_action('admin_menu', 'cities_phones_create_menu');

function cities_phones_create_menu() {

    //create new top-level menu
    add_menu_page('PHONES&CITIES Plugin Settings', 'PHONES&CITIES Settings', 'administrator', __FILE__, 'cities_phones_settings_page', "dashicons-location-alt");

    //call register settings function
    add_action('admin_init', 'register_mysettings');
}

function register_mysettings() {
    //register our settings
    register_setting('cities_phones-settings-group', 'city_1');
    register_setting('cities_phones-settings-group', 'phone_city_1');
    register_setting('cities_phones-settings-group', 'map_city_1');
    register_setting('cities_phones-settings-group', 'city_2');
    register_setting('cities_phones-settings-group', 'phone_city_2');
    register_setting('cities_phones-settings-group', 'map_city_2');
    register_setting('cities_phones-settings-group', 'city_3');
    register_setting('cities_phones-settings-group', 'phone_city_3');
    register_setting('cities_phones-settings-group', 'map_city_3');
}

function cities_phones_settings_page() {
    ?>

    <div class="wrap">
        <h2>PUT CITY&PHONE HERE</h2>
        <form method="post" action="options.php">
    <?php print settings_fields("cities_phones-settings-group")?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">First City</th>
                    <td><input type="text" name="city_1" value="<?php print get_option("city_1")?>" /></td>
                    <th scope="row">Phone Of The First City</th>
                    <td><input type="text" name="phone_city_1" value="<?php print get_option("phone_city_1")?>" /></td>
                </tr>
                <tr style="border-bottom: 1px solid #ddd;">
                    <th scope="row">Map Of The First City (insert code from GoogleMap here 292px*200px)</th>
                    <td colspan="3">
                        <input type="text" name="map_city_1" value="" size="92" /><br/><br/>
    <?php print get_option("map_city_1")?>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Second City</th>
                    <td><input type="text" name="city_2" value="<?php print get_option("city_2")?>" /></td>
                    <th scope="row">Phone Of The Second City</th>
                    <td><input type="text" name="phone_city_2" value="<?php print get_option("phone_city_2")?>" /></td>
                </tr>
                <tr style="border-bottom: 1px solid #ddd;">
                    <th scope="row">Map Of The Second City (insert code from GoogleMap here 292px*200px)</th>
                    <td colspan="3">
                        <input type="text" name="map_city_2" value="" size="92" /><br/><br/>
    <?php print get_option("map_city_2")?>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Third City</th>
                    <td><input type="text" name="city_3" value="<?php print get_option("city_3")?>" /></td>
                    <th scope="row">Phone Of The Third City</th>
                    <td><input type="text" name="phone_city_3" value="<?php print get_option("phone_city_3")?>" /></td>
                </tr>
                <tr style="border-bottom: 1px solid #ddd;">
                    <th scope="row">Map Of The Third City (insert code from GoogleMap here 292px*200px)</th>
                    <td colspan="3">
                        <input type="text" name="map_city_3" value="" size="92" /><br/><br/>
    <?php print get_option("map_city_3")?>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" class="button-primary" value="Save Changes" />
            </p>
        </form>
    </div>
    <?php
}

// get current URL
function get_current_URL() {
    $current_url = 'http';
    $server_https = $_SERVER["HTTPS"];
    $server_name = $_SERVER["SERVER_NAME"];
    $server_port = $_SERVER["SERVER_PORT"];
    $request_uri = $_SERVER["REQUEST_URI"];
    if ($server_https == "on")
        $current_url .= "s";
    $current_url .= "://";
    if ($server_port != "80")
        $current_url .= $server_name . ":" . $server_port . $request_uri;
    else
        $current_url .= $server_name . $request_uri;
    return $current_url;
}

function catLinks($link_String) {
    $linkAr = explode(",", $link_String);
    if (preg_match("/All/", $linkAr[0]) != 0)
        {
        array_shift($linkAr);
        }
    $linkStrNew = implode(",", $linkAr);
    return $linkStrNew;
}

/* Эксперимент с письмами в базу данных */

//регистрация нового типа записей ( новой сущности )
function my_custom_post_product() {
    $args = array();
    register_post_type('mail', $args);
}

add_action('init', 'my_custom_post_mail');

//решистрация  в меню админки
function my_custom_post_mail() {
    $labels = array(
        'name' => _x('Letters', 'post type general name'),
        'singular_name' => _x('Letter', 'post type singular name'),
        'add_new' => _x('Add new', 'mail'),
        'add_new_item' => __('Add new letter'),
        'edit_item' => __('Edit letter'),
        'new_item' => __('New letter'),
        'all_items' => __('All letters'),
        'view_item' => __('View letter'),
        'search_items' => __('Find letter'),
        'not_found' => __('Letters are not found'),
        'not_found_in_trash' => __('no deleted letters'),
        'parent_item_colon' => '',
        'menu_name' => 'Letters'
    );
    $args = array(
        'labels' => $labels,
        'description' => 'Пользовательский тип записей писем',
        'public' => true,
        'menu_position' => 25,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'comments', 'mail_category'),
        'has_archive' => true,
        'menu_icon' => 'dashicons-format-chat'
    );
    register_post_type('mail', $args);
}

add_action('init', 'my_custom_post_mail');

//регистрация таксономий ( категорий ) для нового типа записей
function my_taxonomies_mail() {
    $labels = array(
        'name' => _x('Letters category', 'taxonomy general name'),
        'singular_name' => _x('Letters category', 'taxonomy singular name'),
        'search_items' => __('Find letters category'),
        'all_items' => __('All letters categories'),
        'parent_item' => __("Parent's letters category"),
        'parent_item_colon' => __("Parent's letters category:"),
        'edit_item' => __('Edit letters category'),
        'update_item' => __('Refresh Category'),
        'add_new_item' => __('Add Category'),
        'new_item_name' => __('New Category'),
        'menu_name' => __('Category'),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
    );
    register_taxonomy('mail_category', 'mail', $args);
}

add_action('init', 'my_taxonomies_mail', 0);

//filter letters add
function restrict_books_by_genre() {
    global $typenow;
    $post_type = 'mail'; // change HERE
    $taxonomy = 'mail_category'; // change HERE
    if ($typenow == $post_type)
        {
        $selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
        $info_taxonomy = get_taxonomy($taxonomy);
        wp_dropdown_categories(array(
            'show_option_all' => __("Show All {$info_taxonomy->label}"),
            'taxonomy' => $taxonomy,
            'name' => $taxonomy,
            'orderby' => 'name',
            'selected' => $selected,
            'show_count' => true,
            'hide_empty' => true,
        ));
        };
}

add_action('restrict_manage_posts', 'restrict_books_by_genre');

function convert_id_to_term_in_query($query) {
    global $pagenow;
    $post_type = 'mail'; // change HERE
    $taxonomy = 'mail_category'; // change HERE
    $q_vars = &$query->query_vars;
    if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0)
        {
        $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
        $q_vars[$taxonomy] = $term->slug;
        }
}

add_filter('parse_query', 'convert_id_to_term_in_query');

//filter letters add end
//шорткоды
function shortcode_one_half_open($atts, $content = "") {


    return '<div class="p5 w48 fll tblack">' . $content . '</div>';
}

add_shortcode('one_half', 'shortcode_one_half_open');

function shortcode_last_half_open($atts, $content = "") {


    return '<div class="p5 w48 flr tblack">' . $content . '</div><div class="clear"></div>';
}

add_shortcode('last_half', 'shortcode_one_half_open');

function shortcode_video_centred($atts, $content = "") {


    return '<div class="p5 w100 fll tac dib">' . $content . '</div>';
}

add_shortcode('central_content', 'shortcode_video_centred');
//получение слага текущей страницы
function the_slug() {
    $post_data = get_post($post->ID, ARRAY_A);
    $slug = $post_data['post_name'];
    return $slug;
}

add_action( 'init', 'my_add_excerpts_to_pages' );
function my_add_excerpts_to_pages() {
     add_post_type_support( 'page', 'excerpt' );
}

function theme_styles()  
{ 
  wp_enqueue_style( 'style-css', get_template_directory_uri() . '/style.css', array(), '1.3', 'all' );
}
add_action('wp_enqueue_scripts', 'theme_styles');


add_filter( 'admin_post_thumbnail_html', 'add_featured_image_instruction');
function add_featured_image_instruction( $content ) {
    global $post;
     wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_noncename' );
   // $text = get_post_meta($post->ID, 'image_align_field', true);
    $select = get_post_meta($post->ID, 'image_align_select', true);
    $option1 = '';
    $option2 = '';
    $option3 = '';
    
        if('fn' == $select) { $option2 = 'selected';}
            else { 
                if('flr' == $select) {$option3 = 'selected';}
                    else { $option1 = 'selected';}
            }
    
    //return $content .= '<div id="myplugin_new_field_div" class="misc-pub-section" style="overflow:hidden; transition: all 0.3s; padding:0; border-top-style:solid; border-top-width:1px; border-top-color:#EEEEEE; border-bottom-width:0px;"><div style="font-weight: bold; padding:9px 0 9px;">Type correct image align(left, right, center):</div><input name="image_align_field" id="image_align_field" type="text" value="'.$text.'"><select name="image_align_select" id="image_align_select"><option value="fll" '.$option1.'>Float left</option><option value="fn" '.$option2.'>Float none</option><option value="flr" '.$option3.'>Float right</option></select></div>';
    return $content .= '<div id="myplugin_new_field_div" class="misc-pub-section" style="overflow:hidden; transition: all 0.3s; padding:0; border-top-style:solid; border-top-width:1px; border-top-color:#EEEEEE; border-bottom-width:0px;"><div style="font-weight: bold; padding:9px 0 9px;">Select image align:</div><select style="width:99%" name="image_align_select" id="image_align_select"><option value="fll" '.$option1.'>Float left</option><option value="fn" '.$option2.'>Float none</option><option value="flr" '.$option3.'>Float right</option></select></div>';
}
add_action( 'save_post', 'wpse_52193_save_postdata' );
function wpse_52193_save_postdata( $post_id ) {
   
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return;

    if ( !wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename( __FILE__ ) ) )
        return;

    //$mydata = $_POST['image_align_field'];
    //update_post_meta($post_id, 'image_align_field', $mydata);
    $mydata2 = $_POST['image_align_select'];
    update_post_meta($post_id, 'image_align_select', $mydata2);
}
?>