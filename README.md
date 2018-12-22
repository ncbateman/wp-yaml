# wp-yaml

wp-yaml is a plugin to facilitate registration of WordPress resources within plugins and themes, using YAML. 

# Plugin / theme registration. 

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
