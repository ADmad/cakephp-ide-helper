<?php
namespace IdeHelper\Generator\Task;

use Cake\Core\App;
use Cake\Core\Plugin;
use Cake\Filesystem\Folder;
use Cake\ORM\Table;
use IdeHelper\Generator\Directive\Override;
use IdeHelper\Utility\AppPath;

class BehaviorTask implements TaskInterface {

	const CLASS_TABLE = Table::class;

	/**
	 * @var string[]
	 */
	protected $aliases = [
		'\\' . self::CLASS_TABLE . '::addBehavior(0)',
	];

	/**
	 * @return \IdeHelper\Generator\Directive\BaseDirective[]
	 */
	public function collect() {
		$map = [];

		$behaviors = $this->collectBehaviors();
		foreach ($behaviors as $name => $className) {
			$map[$name] = '\\' . $className . '::class';
		}

		ksort($map);

		$result = [];
		foreach ($this->aliases as $alias) {
			$directive = new Override($alias, $map);
			$result[$directive->key()] = $directive;
		}

		return $result;
	}

	/**
	 * @return string[]
	 */
	protected function collectBehaviors() {
		$behaviors = [];

		$folders = array_merge(App::core('ORM/Behavior'), AppPath::get('Model/Behavior'));
		foreach ($folders as $folder) {
			$behaviors = $this->addBehaviors($behaviors, $folder);
		}

		$plugins = Plugin::loaded();
		foreach ($plugins as $plugin) {
			$folders = AppPath::get('Model/Behavior', $plugin);
			foreach ($folders as $folder) {
				$behaviors = $this->addBehaviors($behaviors, $folder, $plugin);
			}
		}

		ksort($behaviors);

		return $behaviors;
	}

	/**
	 * @param array $behaviors
	 * @param string $folder
	 * @param string|null $plugin
	 *
	 * @return string[]
	 */
	protected function addBehaviors(array $behaviors, $folder, $plugin = null) {
		$folderContent = (new Folder($folder))->read(Folder::SORT_NAME, true);

		// This suffices as the return value is $this (calling Table class) anyway for chaining.
		$className = Table::class;

		foreach ($folderContent[1] as $file) {
			preg_match('/^(.+)Behavior\.php$/', $file, $matches);
			if (!$matches) {
				continue;
			}
			$name = $matches[1];
			if ($plugin) {
				$name = $plugin . '.' . $name;
			}

			$behaviors[$name] = $className;
		}

		return $behaviors;
	}

}
