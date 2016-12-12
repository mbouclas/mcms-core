<?php

namespace Mcms\Core\Widgets;
use Blade,\Widget as W;
use Mcms\Core\Exceptions\InvalidWidgetException;
use Illuminate\Support\Collection;
use App;
/**
 * Class Widget
 * @package Mcms\Widgets
 */
class Widget
{

    /**
     * @var Collection
     */
    public $widgets;

    /**
     * @var CompileString
     */
    protected $stringCompiler;

    /**
     * Widget constructor.
     */
    public function __construct()
    {
        $this->widgets = new Collection();
        $this->stringCompiler = new CompileString(App::make('files'), App::make('path').'/storage/views');
    }

    /**
     * Register the @Widget directive with Blade
     */
    public static function registerDirective()
    {
        Blade::directive('Widget', function($name) {
//            $namePattern = "/\\(['\"](([a-zA-Z]+))['\"][,)]/";//This will extract the name of the widget from the @Widget('name')
            $namePattern = '/[\'"]([^"\']*)["\']\s?(?:[,?\s](.*))?/';//This will extract the name of the widget from the @Widget('name')
            $argsPattern = "/,(\[.+])/";//This will extract any possible arguments from the directive
            preg_match($namePattern, $name,$matches);
            preg_match($argsPattern, $name,$argsFound);

            $widget = W::lookup($matches[1]);//find the class name based on the widget name
            if (is_null($widget)){
                return null;
            }

            //setup the run command ('CLASS',[args])
            $name = "('{$widget->instance}'";
            if (isset($argsFound[0])){
                $name .= "){$argsFound[0]}";
            } else {
                $name .= ")";
            }

            //call the widget command
            return "<?php echo app(\"arrilot.widget\")->run({$name}); ?>";
        });
    }

    /**
     * Creates a directive with which you can render directives from a model
     * or a string @Compile($var)
     */
    public static function registerWidgetCompiler()
    {
        Blade::directive('Compile', function($expression) {

            return '<?php '.
            '$generated = Blade::compileString('.$expression.');'.
            'ob_start();'.
            'eval(\'?>\'.$generated);'.
            '$content = ob_get_clean();'.
            'echo $content;'.
            '?>';
        });
    }

    /**
     * Create a new widget and register it
     * @param $widget
     */
    public function create($widget){
        try {
            $newWidget = new WidgetInstance($widget);
            $this->register($newWidget->name,$newWidget);
        }
        catch (InvalidWidgetException $e){
            //Figure out what to do
            print_r($e);
        }



        return $this;
    }

    /**
     * Add this widget with our system
     * @param string $alias
     * @param WidgetInstance $widget
     */
    protected function register($alias, WidgetInstance $widget){
        $this->widgets[$alias] = $widget;
        return $this;
    }

    /**
     * Find the class name from all registered widgets based on the widget name
     * @param $name
     * @return null
     */
    public function lookup($name)
    {
        $found = $this->widgets->where('name',$name);

        if (! $found){
            return null;
        }

        return ($found->first()) ?: null;
    }

    /**
     * Compile a string containing blade syntax. Works with tokens only
     * e.g. compileFromString('{{name}}',['name'=>'Michael'])
     * @param $string
     * @param array $args
     * @return string
     * @throws \Exception
     */
    public function compileFromString($string, array $args = [])
    {
        return $this->stringCompiler->compileWiths($string, $args);
    }

    /**
     * Compile a string containing blade syntax. Works with directives
     * @param $string
     * @return string
     */
    public function compileString($string)
    {
        return Blade::compileString($string);
    }

}