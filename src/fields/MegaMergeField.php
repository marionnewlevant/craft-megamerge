<?php
/**
 * MegaMerge plugin for Craft CMS 3.x
 *
 * Merge this that and the other...
 *
 * @link      http://marion.newlevant.com
 * @copyright Copyright (c) 2018 Marion Newlevant
 */

namespace marionnewlevant\megamerge\fields;

use marionnewlevant\megamerge\MegaMerge;

use Craft;
use craft\fields\Table;
use craft\helpers\Json;
use craft\web\assets\tablesettings\TableSettingsAsset;

/**
 * @author    Marion Newlevant
 * @package   MegaMerge
 * @since     1.0.0
 */
class MegaMergeField extends Table
{
    // Public Properties
    // =========================================================================

    /**
     * @var array|null The columns that should be shown in the table
     */
    public $columns = [
        'col1' => [
            'heading' => 'Key',
            'handle' => 'key',
            'width' => '20%',
            'type' => 'singleline'
        ],
        'col2' => [
            'heading' => 'Value',
            'handle' => 'value',
            'width' => '80%',
            'type' => 'multiline'
        ],
    ];

    /**
     * @var array The default row values that new elements should have
     */
    public $defaults = [
    ];

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('mega-merge', 'MegaMerge');
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        $typeOptions = [
            'multiline' => Craft::t('app', 'Multi-line text'),
            'singleline' => Craft::t('app', 'Single-line text'),
        ];

        // Make sure they are sorted alphabetically (post-translation)
        asort($typeOptions);

        $columnSettings = [
            'heading' => [
                'heading' => Craft::t('app', 'Column Heading'),
                'type' => 'singleline',
                'autopopulate' => 'handle'
            ],
            'handle' => [
                'heading' => Craft::t('app', 'Handle'),
                'code' => true,
                'type' => 'singleline'
            ],
            'width' => [
                'heading' => Craft::t('app', 'Width'),
                'code' => true,
                'type' => 'singleline',
                'width' => 50
            ],
            'type' => [
                'heading' => Craft::t('app', 'Type'),
                'class' => 'thin',
                'type' => 'select',
                'options' => $typeOptions,
            ],
        ];

        $view = Craft::$app->getView();

        $view->registerAssetBundle(TableSettingsAsset::class);
        $view->registerJs('new Craft.TableFieldSettings(' .
            Json::encode($view->namespaceInputName('columns'), JSON_UNESCAPED_UNICODE) . ', ' .
            Json::encode($view->namespaceInputName('defaults'), JSON_UNESCAPED_UNICODE) . ', ' .
            Json::encode($this->columns, JSON_UNESCAPED_UNICODE) . ', ' .
            Json::encode($this->defaults, JSON_UNESCAPED_UNICODE) . ', ' .
            Json::encode($columnSettings, JSON_UNESCAPED_UNICODE) .
            ');');

        $columnsField = $view->renderTemplateMacro('_includes/forms', 'editableTableField', [
            [
                'label' => Craft::t('app', 'Table Columns'),
                'instructions' => Craft::t('mega-merge', 'These are the columns your table will have.'),
                'id' => 'columns',
                'name' => 'columns',
                'cols' => $columnSettings,
                'rows' => $this->columns,
                'initJs' => false,
                'static' => true // make it not editable
            ]
        ]);

        $defaultsField = $view->renderTemplateMacro('_includes/forms', 'editableTableField', [
            [
                'label' => Craft::t('app', 'Default Values'),
                'instructions' => Craft::t('app', 'Define the default values for the field.'),
                'id' => 'defaults',
                'name' => 'defaults',
                'cols' => $this->columns,
                'rows' => $this->defaults,
                'initJs' => false
            ]
        ]);

        return $view->renderTemplate('_components/fieldtypes/Table/settings', [
            'field' => $this,
            'columnsField' => $columnsField,
            'defaultsField' => $defaultsField,
        ]);
    }
}
