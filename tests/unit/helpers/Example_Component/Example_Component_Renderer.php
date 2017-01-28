<?php
/**
 * Example Component Renderer
 *
 * @package  Lift\Playbook\UI\Components\Example
 */
namespace Lift\Playbook\UI\Components;
?>

<h1>Example Output</h1>

<ul>
	<li>
		<span class="label">ExampleComponent::$string</span>
		<span class="value"><?php echo $this->string; ?></span>
	</li>
	<li>
		<span class="label">ExampleComponent::$integer</span>
		<span class="value"><?php echo $this->integer; ?></span>
	</li>
	<li>
		<span class="label">ExampleComponent::$double</span>
		<span class="value"><?php echo $this->double; ?></span>
	</li>
	<li>
		<span class="label">ExampleComponent::$boolean</span>
		<span class="value"><?php echo $this->boolean; ?></span>
	</li>
	<li>
		<span class="label">ExampleComponent::$array</span>
		<span class="value"><?php echo $this->array; ?></span>
	</li>
	<li>
		<span class="label">ExampleComponent::$object</span>
		<span class="value"><?php echo $this->object; ?></span>
	</li>
</ul>
