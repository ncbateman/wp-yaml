# wp-yaml

## Introduction 
wp-yaml is a plugin to facilitate registration of WordPress resources within plugins and themes, using YAML. 

## Plugin / theme registration. 

To register a plugin or theme with wp-yaml, the wp-yaml plugin must be installed and activated. 

The theme / plugin must contain a configuration directory commonly called 'config' example tree shown below:

```
example-config-dir
├── Actions
│   ├── Action1.yaml
│   └── Action2.yaml
├── Filters
│   ├── Filter1.yaml
│   └── Filter2.yaml
├── PostTypes
│   ├── PostType1.yaml
│   └── PostType2.yaml
└── Routes
    ├── Route1.yaml
    └── Route2.yaml
```

The theme / plugin must contain the following snippet of code: 

```
use WpYaml\WpYaml as WpYaml;

add_action('muplugins_loaded', function () {

	$wp_yaml = WpYaml::init();

	$wp_yaml->register('/path/to/plugin/config/directory/');
  
});
```

Once registered, the wp-yaml plugin with ingest and register all resources defined in the configuration directories. 


## Registering Actions

Action config files must be structed as follows:

```
---
callback: Full\Callback\Class\Namespace
actions:
    action_a:
      hook: admin_enqueue_scripts
      method: method_name_a
    action_b:
      hook: admin_enqueue_scripts
      method: method_name_b
      priority: 5
      args: 1
```

You may register as many methods per callback as you like, but only one callback class per config file. You may have as many Action config files as you like to utilise multiple callback classes.

The priority and args values are optional as per wordpress.


## Registering Filters

Filter config files must be structed as follows:

```
---
callback: Full\Callback\Class\Namespace
filters:
    filter_a:
      hook: admin_enqueue_scripts
      method: method_name_a
      priority: 5
      args: 1
    filter_b:
      hook: admin_enqueue_scripts
      method: method_name_b
```

As with actions, you may register as many methods per callback as you like, but only one callback class per config file. You may have as many Filter config files as you like to utilise multiple callback classes.

The priority and args values are optional as per wordpress.

## Registering Routes

Routes have a very light config file as most of the work is done by the route class which should be an extension of *WP_REST_Controller*. 

Route config files must be structured as follows: 

```
---
routes:
  route_a: Full\WP_REST_Controller\Class\NamespaceA
  route_b: Full\WP_REST_Controller\Class\NamespaceB
```

## Registering PostTypes

Post Types are entirely defined in the config file and use no callback class.  

Post Type config files must be structured as follows: 

```
---
example-posttype:
  labels:
    name: Example Post Types
    singular_name: Example Post Type
    menu_name: Example Post Types
    name_admin_bar: Example Post Types
    add_new: Add New
    add_new_item: Add New Example Post Type
    edit_item: Edit Example Post Type
    new_item: New Example Post Type
    view_item: View Example Post Type
    search_items: Search Example Post Types
    not_found: No Example Post Types found
    not_found_in_trash: No Example Post Types found in trash
    all_items: All Example Post Types
    parent_item: Parent Example Post Type
    parent_item_colon: 'Parent Example Post Type:'
    archive_title: Example Post Types
  description: Example Post Type Description
  public: true
  publicly_queryable: true
  exclude_from_search: false
  show_in_nav_menus: false
  show_ui: true
  show_in_menu: true
  show_in_admin_bar: true
  menu_position:
  menu_icon: dashicons-admin-site
  can_export: true
  delete_with_user: false
  hierarchical: false
  has_archive: example_post_type
  query_var: example_post_type
  capability_type: page
  map_meta_cap: true
  rewrite:
    slug: example-posttype
    with_front: false
    pages: true
    feeds: true
    ep_mask: 1
  supports:
  - custom-fields
  - revisions
  - title
```

You may have as many post types per config file as you like, however, for simplicity of organisation 1 per file feels best.
