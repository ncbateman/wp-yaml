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
```

You may register as many methods per callback as you like, but only one callback class per config file. You may have as many Action config files as you like to utilise multiple callback classes.


## Registering Filters

Filter config files must be structed as follows:

```
---
callback: Full\Callback\Class\Namespace
filters:
    filter_a:
      hook: admin_enqueue_scripts
      method: method_name_a
    filter_b:
      hook: admin_enqueue_scripts
      method: method_name_b
```

As with actions, you may register as many methods per callback as you like, but only one callback class per config file. You may have as many Filter config files as you like to utilise multiple callback classes.
