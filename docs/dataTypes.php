<?php
$page = "dataTypes";
require_once("templates/header.php");
?>

<div class="container">
	<div class="row">

		<div class="span3 bs-docs-sidebar" id="pagenav">
			<ul class="nav nav-list bs-docs-sidenav" data-spy="affix">
				<li class="active"><a href="#overview"><i class="icon-chevron-right"></i> Overview</a></li>
				<li><a href="#anatomy"><i class="icon-chevron-right"></i> Anatomy of a Data Type</a></li>
				<li><a href="#filesAndFolders"><i class="icon-chevron-right"></i> - Files and Folders</a></li>
				<li><a href="#js"><i class="icon-chevron-right"></i> - JavaScript</a></li>
				<li><a href="#php"><i class="icon-chevron-right"></i> - PHP</a></li>
				<li><a href="#languageFiles"><i class="icon-chevron-right"></i> - Language Files</a></li>
				<li><a href="#phpClass"><i class="icon-chevron-right"></i> <b>The PHP Class</b></a></li>
				<li><a href="#phpClassExample"><i class="icon-chevron-right"></i> - Example: GUID Data Type</a></li>
				<li><a href="#phpClassVars"><i class="icon-chevron-right"></i> - Overridable Class Variables</a></li>
				<li><a href="#phpClassMethods"><i class="icon-chevron-right"></i> - Overridable Class Methods</a></li>
				<li><a href="#phpClassNonOverridableMethods"><i class="icon-chevron-right"></i> - Non-overridable Class Methods</a></li>
				<li><a href="#jsModule"><i class="icon-chevron-right"></i> <b>The JS Module</b></a></li>
				<li><a href="#jsModuleExample"><i class="icon-chevron-right"></i> - Example: Alphanumeric</a></li>
				<li><a href="#jsModuleFunctions"><i class="icon-chevron-right"></i> - Registration Functions</a></li>
				<li><a href="#jsModulePubSub"><i class="icon-chevron-right"></i> - Pub/Sub &amp; Event List</a></li>
				<li><a href="#practicalTips"><i class="icon-chevron-right"></i> <b>Practical Tips</b></a></li>
				<li><a href="#populatingCols"><i class="icon-chevron-right"></i> - Populating "Example" and "Options" columns</a></li>
				<li><a href="#availableResources"><i class="icon-chevron-right"></i> Available JS Resources</a></li>
				<li><a href="#updatingUI"><i class="icon-chevron-right"></i>Adding your Data Type</a></li>
				<li><a href="#contribute"><i class="icon-chevron-right"></i> How to Contribute</a></li>
			</ul>
		</div>
		<div class="span9">

			<a id="overview"></a>
			<section>
				<div class="page-header">
					<h1>Data Types</h1>
				</div>
				<p class="lead">
					Provide new types of data for generation.
				</p>
			</section>
 
			<section id="overview">
				<h2>Overview</h2>
				<p>
					This page explains how to add your own data types so you can use the Data Generator to generate pretty much whatever crazy 
					stuff you want.
				</p>
				<p>
					Data Types are <b>self-contained plugins</b> that generate a single random data item, like a name, email address, country name,
					country code, image, picture, URL, barcode image, binary string - really anything you want. Data Types can offer basic 
					functionality, like the <code>Email Address</code> Data Type which has no options, examples or help doc, or they can 
					be more advanced, like the <code>Date</code> Data Type, which contains examples of date formats for easy generation, 
					and contains a date picker dialog (jQuery UI). Data Types can be standalone and generate data that has no bearing on 
					other fields - like the <code>Alpha Numeric</code> Data Type - or make decisions about its content based on other fields 
					in the data set, like <code>Region</code>, which intelligently generates a region within whatever country has been randomly 
					generated for that row. Finally, if you want to get <i>really</i> fancy, you can even create Data Types that 
					generate content based on previously generated <i>row</i> data, like the <code>Tree</code> Data Type that 
					creates a tree-like data structure by mapping the ID of each row to a single parent row ID.
				</p>
				<p>
					<b>Data Types have both a PHP and (optional) JS component</b>. The PHP is used to do the actual generation; the JS is used 
					for creating the UI and saving/loading the Data Type data.
				</p>
				<p>
					When creating your new Data Type, you can add anything you need from client-side validation to custom dynamic JS/DOM 
					manipulation. You can also generate different content based on the selected Export Type (SQL, XML etc). It's a pretty 
					flexible system, so hopefully you won't run into any brick walls. And if you do, you can just <a href="contribute.php">drop 
					me a line</a> and explain the shortcomings. 
				</p>
				<p>
					Lastly, I tried to make the process of adding Data Types as simple and as sandboxed as possible. The Core script does 
					an awful lot for you: all you really need to do is follow the instructions below and maybe look at the existing Data Types 
					for inspiration. Once you wrap your head about how it all fits together, developing new Data Types should be pretty
					straightforward.
				</p>
				<p>
					Alrighty! Let's start with looking at the actual files and folders that go into a Data Type.
				</p>
			</section>

			<hr />

			<section id="anatomy">
				<h2>Anatomy of a Data Type</h2>
				<p>
					Now let's do a high-level view of what goes into a module: the files and folders, the JS + PHP components
					and how the translations / internationalization works. We'll get into the details about the code in the following
					sections.
				</p>
			</section>

			<section id="filesAndFolders">
				<h3>Files and Folders</h3>
				<p>
					All Data Types are found in the <code>/resources/plugins/dataTypes/</code> folder. Each Data Type has its own folder, 
					which acts as the namespace for the JS and PHP code. What I mean is that the exact string you choose for the folder (like 
					<code>AlphaNumeric</code> or <code>StreetAddress</code>) <i>has to be</i> used in your JS module creation and PHP
					class definition. I'll explain all that below.
				</p>
				<p>
					A Data Type has the following <b>required</b> files. Let's assume the folder name is <code>MyNewDataType</code>.
				</p>
				<ul>
					<li><code>/resources/plugins/dataType/MyNewDataType.js</code>: this file can actually be called whatever you want, but for 
						consistency and for keeping reading the Web Inspector / Firebug net panel, I'd name them like this. You can have 
						as many JS files as you want, but one is almost certainly enough.</li>
					<li><code>/resources/plugins/dataType/MyNewDataType.class.php</code>: this contains your <code>DataType_MyNewDataType</code>
						class, which handles all necessary server-side code: the data generation and any markup you want available in the 
						generator webpage. More info about all that below.</li>
					<li><code>/resources/plugins/dataType/lang/en.php</code>: A PHP file containing a single array (hash) that lists all 
						strings used in your module.</li>
				</ul>
				<p>
					You can also include any custom CSS files you want. See the PHP class definition below for more information
				</p>
			</section>

			<section id="js">
				<h3>JavaScript</h3>
				<p>
					The JS module for your Data Type does the following:
				</p>
				<ul>
					<li>Registers itself with the <code>Manager</code> JS component, to allow it to publish and subscribe to messages; i.e.
						to interact with the Core script and detect when certain user interface events happen.</li>
					<li>Save and load data for each row that has your Data Type selected.</li>
					<li>Perform whatever validation is required to ensure the user fills in the Data Type row properly.</li>
					<li>Perform any additional UI frills, like hiding/showing/disabling/enabling content based on information entered 
						by the user in the page.</li>
				</ul>
			</section>

			<section id="php">
				<h3>PHP</h3>
				<p>
					The PHP class for your Data Type handles the following functionality:
				</p>
				<ul>
					<li>Initial installation of the module, if it needs to do anything special.</li>
					<li>Specifies in which section and what order in the Data Types dropdown your Data Type should appear.</li>
					<li>Specifies what JS and CSS files should be included for the Data Type when the generator is loaded.</li>
					<li>Creates whatever HTML is needed for the <code>Example</code> and <code>Options</code> columns in the generator table.</li>
					<li>Creates whatever HTML should be included in the Help section of the dialog window.</li>
					<li>Actually generate the random data for that Data Type.</li>
					<li>Specifies the process order of the Data Type. When the random data is generated, it's generated row by row. Within each
						row, each Data Type is generated in waves. The first wave are fields that have no dependencies with other row types; 
						the second and later waves may all depend on previous waves. That way, a Data Type that needs to know if another field 
						has a particular value can be sure that that value is actually loaded, and use that information in generating 
						the random snippet for that column and row. For example, a <code>Region</code> field can check to see if a
						<code>Country</code> field has been included, and if so, generate a random region within the country for that row.</li>
				</ul>
			</section>

			<section id="languageFiles">
				<h3>Language Files</h3>
				<p>
					All text strings that appear in your module should be pulled from a language file. It's very simple. Just create a 
					file called <code>en.php</code> in your <code>/resources/plugins/dataTypes/[data type folder]/lang/</code> folder.
					That file should contain a single <code>$L</code> hash, like so:
				</p>
	
<pre class="prettyprint linenums">
&lt;?php 

$L = array();
$L["DATA_TYPE_NAME"] = "Alphanumeric";
$L["example_CanPostalCode"] = "(Can. Postal code)";
$L["example_Password"] = "(Password)";

// ...
</pre>

				<p>
					Once you do that, the Data Generator automatically makes that information accessible to your PHP and JS 
					code. I'll explain how that works in the following sections.
				</p>
			</section>

			<hr />

			<section id="phpClass">
				<h2>The PHP Class</h2>
				<p>
					All plugins - <code>Data Types</code>, <code>Export Types</code> and <code>Country</code> plugins have to extend 
					a base, abstract class defined by the core code. Hopefully you know what this means, but if not - time for some 
					Googling! Simply put, abstract classes are a mechanism to help ensure that the class being defined has a proper 
					footprint and contains all the functionality that's expected and required.
				</p>

				<p>
					For Data Types, take a look at this file: <code>/resources/classes/DataTypePlugin.class.php</code>. That's the 
					class you'll need to extend.
				</p>
			</section>

			<section id="phpClassExample">
				<h3>Example: GUID Data Type</h3>
				<p>
					Now rather than blather on about your Data Type PHP class in the abstract, let's look at an actual implementation
					first. If you want to see the complete list of available variables and methods, check out the source code 
					of the Data Type abstract class (<code>/resources/classes/DataTypePlugin.abstract.class.php</code>). It's well 
					documented.
				</p>
				<p>
					This is the PHP class for the <code>GUID</code> class. It's a simple Data Type that generates a random GUID string.
					Maybe first try it out in the script to see what it does.
				</p>

<pre class="prettyprint linenums">
&lt;?php

/**
 * @package DataTypes
 */

class DataType_GUID extends DataTypePlugin {
	protected $isEnabled = true;
	protected $dataTypeName = "GUID";
	protected $dataTypeFieldGroup = "numeric";
	protected $dataTypeFieldGroupOrder = 50;
	private $generatedGUIDs = array();

	public function generate($generator, $generationContextData) {
		$placeholderStr = "HHHHHHHH-HHHH-HHHH-HHHH-HHHH-HHHHHHHH";
		$guid = Utils::generateRandomAlphanumericStr($placeholderStr);

		// pretty sodding unlikely, but just in case!
		while (in_array($guid, $this->generatedGUIDs)) {
			$guid = Utils::generateRandomAlphanumericStr($placeholderStr);
		}
		$this->generatedGUIDs[] = $guid;
		return array(
			"display" => $guid
		);
	}

	public function getHelpHTML() {
		return "&lt;p&gt;{$this->L["help"]}&lt;/p&gt;";
	}

	public function getDataTypeMetadata() {
		return array(
			"SQLField" => "varchar(36) NOT NULL",
			"SQLField_Oracle" => "varchar2(36) NOT NULL"
		);
	}
}
</pre>

				<p>
					Let's look at each line in turn.
				</p>

				<ul>
					<li><code>class DataType_GUID extends DataTypePlugin</code>: our class definition. All Data Type class names
						must for of the following format: <code>DataType_[folder]</code> - where <i>folder</i> is the name
						of the Data Type folder. Pretty straightforward. Also, note that it extends the DataTypePlugin base class.
						That's required.</li>
					<li><code>$isEnabled</code>: this var explicitly enables/disables the module. In case you're tinkering around with 
						a new Data Type, sometimes you may not want it to show up in the UI - so you'd just set this to 
						<code>false</code>.</li>
					<li><code>$dataTypeName</code>: this is the human-readable name of your module. It can be in whatever
						language you want, but we prefer English as the default language string. The value you enter in this variable
						is <i>automatically overridden</i> if the current selected language has the following value in the language file:
						<code>$L["DATA_TYPE_NAME"] = "New Name";</code> This provides a simple mechanism to provide alternative translations
						of your Data Type names.</li>
					<li><code>$dataTypeFieldGroup</code>: in the Data Type dropdowns in the generator, you'll notice that the Data 
						Types are all grouped. This variable determines which group your Data Type should appear in. You can choose any of the 
						following strings: <code>human_data</code>, <code>geo</code>, <code>text</code>, <code>numeric</code>, <code>math</code>, 
						<code>other</code>. If you feel that you need a new group for your Data Type, <a href="contribute.php">drop me a line</a>.
					</li>
					<li><code>$dataTypeFieldGroupOrder</code>: this determines where in the list your Data Type should appear. Look at the 
						the values for other Data Types to figure out what value to enter. I spaced them all out with 10 in between to allow you 
						to insert your Data Type at any point in the list.</li>
				</ul>	

				<p>
					So far so good. The next line, <code>$generatedGUIDs</code> is a custom private var for use by this Data Type only. Don't worry 
					about it.
				</p>

				<p>
					Now lets look at the methods:
				</p>

				<ul>
					<li><code>public function generate($generator, $generationContextData)</code>: this is the main generation
						function for the Data Type. It's passed two parameters:
						<ol>
							<li><b>The current Generator instance</b>. Behinds the scenes, the data generation is all managed by the 
								<code>Generator</code> class, found here: <code>/resources/classes/Generator.class.php</code>. This is a very helpful class - 
								it contains various utility methods for finding out about the current data set being generated. However, the 
								<code>GUID</code> class doesn't need it.</li>
							<li><b>The generation context data</b>. The <code>Generator</code> generates the data sets row by row. Each row contains 
								one or more Data Types. This variable contains all the Data Types generated so far for the current 
								row. Any Data Type can choose to return additional meta data for a particular generated atomic data - e.g. a 
								Region could choose to return the <i>Country</i> to which is belongs. This second function param contains all that
								information. Lastly, if a Data Type has dependencies on previous Data Types in the row, it needs to 
								set the <code>protected $processOrder = X;</code> class variable. See the Data Type plugin abstract class
								for more information about that advanced feature - or look at the <code>Region</code> plugin for an example
								of how it's used.
							</li>
						</ol>
					</li>
					<li>
						<code>public function getHelpHTML()</code>: this optional function is used to return whatever help text you want for your 
						Data Type. Note that the returned string references a <code>$L</code> class variable: <code>$this->L["help"]</code>. The 
						<code>$L</code> variable is populated with the <i>current</i> language file automatically when the Data Type is instantiated.
						This mechanism is taken care of for you - you can safely refer to <code>$this->L</code> throughout your own class.
					</li>
					<li>
						<code>public function getDataTypeMetadata()</code>: this optional function returns additional meta information about 
						your Data Type. Right now it's really only used for the <code>SQL</code> Export Type. When the user selects SQL, the code needs to 
						know how large a database field should be created for the data. As such, this function returns that information - for both
						generic SQL and Oracle SQL, so the Export Type can do it's job. As mentioned, this is not a required function. If it wasn't
						supplied, the <code>SQL</code> Export Type would just provide its best guess. 
					</li>
				</ul>


				<p>
					And that's it for our example. The following sections go into greater depth regarding the class member vars and 
					methods. There's a lot more you can do.
				</p>

			</section>

			<section id="phpClassVars">
				<h2>Class Variable List</h2>
				<p>
					Alright! Here's the full list of class vars that have special meaning.
				</p>

				<table class="table table-striped">
					<thead>
						<tr>
							<th>Var</th>
							<th>Req/Opt</th>
							<th>Type</th>
							<th>Explanation</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>$dataTypeName</td>
							<td><span class="label label-success">required</span></td>
							<td>string</td>
							<td>The human-readable name of the Data Type used in the UI. Note: the <code>$L["DATA_TYPE_NAME"]</code>
								defined in a language file will override this value.</td>
						</tr>
						<tr>
							<td>$dataTypeFieldGroup</td>
							<td><span class="label label-success">required</span></td>
							<td>string</td>
							<td>
								Data Types are grouped together in the Data Type dropdowns in the UI. This variable lets the system
								know to which group your Data Type should belong. Possible values are: <code>human_data</code>, 
								<code>geo</code>, <code>text</code>, <code>numeric</code>, <code>math</code>, <code>other</code>. 
								If you feel that you need a new group for your Data Type, <a href="contribute.php">drop me a line</a>.
							</td>
						</tr>
						<tr>
							<td>$dataTypeFieldGroupOrder</td>
							<td><span class="label label-success">required</span></td>
							<td>integer</td>
							<td>The order in which the Data Type should appear within the group specified by the previous field.</td>
						</tr>
						<tr>
							<td>$isEnabled</td>
							<td><span class="label label-info">optional</span></td>
							<td>boolean</td>
							<td>Hides / shows the module from the interface. Note, you'll need to refresh the list of 
								plugins after changing this value.</td>
						</tr>
						<tr>
							<td>$jsModules</td>
							<td><span class="label label-info">optional</span></td>
							<td>array</td>
							<td>An array of JS filenames, all found in the Data Type folder.</td>
						</tr>
						<tr>
							<td>$cssFiles</td>
							<td><span class="label label-info">optional</span></td>
							<td>array</td>
							<td>An array of CSS filenames, all found in the Data Type folder.</td>
						</tr>
						<tr>
							<td>$L</td>
							<td><span class="label label-important">auto-generated</span></td>
							<td>array</td>
							<td>Do NOT define this variable. When the Data Type is instantiated, this variable
								is auto-generated and populated with the appropriate language file.</td>
						</tr>
					</tbody>
				</table>
			</section>

			<section id="phpClassMethods">
				<h2>Class Method List</h2>

				<h3>generate()</h3>

				<table class="table">
					<tbody>
						<tr>
							<th>Req/Opt</th>
							<td><span class="label label-success">required</span></td>
						</tr>
						<tr>
							<th>Params</th>
							<td>
								<ol>
									<li>
										<b>$generator</b>: the <code>Generator</code> object, through which a Data Type can call the various available 
										public methods. See <code>/resources/classes/Generator.class.php</code>.
									</li>
									<li>
										<b>$generationOptions</b>:
										A hash of information relating to the generation context. Namely:<br />
										<code>rowNum</code>: the row number in the generated content (indexed from 1)<br />
										<code>generationOptions</code>: whatever options were passed for this particular 
										row and data type; i.e. whatever information was returned by getRowGenerationOptions(). This data can 
										be empty or contain anything needed - in whatever format. By default, this is set to null.<br />
										<code>existingRowData</code>: data already generated for the row.
									</li>
								</ol>
							</td>
						</tr>
						<tr>
							<th>Explanation</th>
							<td>
								This does the work of actually generating a random data snippet. Data Types have to return a hash with at 
								least one key: "display". They can also load up the hash with whatever else they want, if they want to 
								provide additional meta data to other Data Types that are being generated on that row (e.g. Country, 
								passing its country_slug info to Region)
							</td>
						</tr>
					</tbody>
				</table>


				<h3>__construct()</h3>

				<table class="table">
					<tbody>
						<tr>
							<th>Req/Opt</th>
							<td><span class="label label-info">optional</span></td>
						</tr>
						<tr>
							<th>Params</th>
							<td>
								<b>$runtimeContext</b>: Data Types classes are instantiated at different times in the code. This parameter
								is a string that describes the context in which it's being instantiated: <code>ui</code> / <code>generation</code>
							</td>
						</tr>
						<tr>
							<th>Explanation</th>
							<td>
								An optional constructor. Note: this should always call <code>parent::__construct($runtimeContext);</code>.
							</td>
						</tr>
					</tbody>
				</table>


				<h3>install()</h3>

				<table class="table">
					<tbody>
						<tr>
							<th>Req/Opt</th>
							<td><span class="label label-info">optional</span></td>
						</tr>
						<tr>
							<th>Params</th>
							<td>None</td>
						</tr>
						<tr>
							<th>Explanation</th>
							<td>
								This is called once during the initial installation of the script, or when the installation is reset (which 
								is effectively a fresh install). It is called AFTER the Core tables are installed, and you can rely on 
								<code>Core::$db</code> having been initialized and the database connection having been set up.
							</td>
						</tr>
					</tbody>
				</table>


				<h3>getExampleColumnHTML()</h3>

				<table class="table">
					<tbody>
						<tr>
							<th>Req/Opt</th>
							<td><span class="label label-info">optional</span></td>
						</tr>
						<tr>
							<th>Params</th>
							<td>None</td>
						</tr>
						<tr>
							<th>Explanation</th>
							<td>
								If the Data Type wants to include something in the Example column, it should return the raw HTML via this 
								function. If this function isn't defined (or it returns an empty string), the string "No examples available." 
								will be outputted in the cell. This is used for inserting static content into the appropriate spot in the 
								table; if the Data Type needs something more dynamic, it should subscribe to the appropriate event.
							</td>
						</tr>
					</tbody>
				</table>


				<h3>getOptionsColumnHTML()</h3>

				<table class="table">
					<tbody>
						<tr>
							<th>Req/Opt</th>
							<td><span class="label label-info">optional</span></td>
						</tr>
						<tr>
							<th>Params</th>
							<td>None</td>
						</tr>
						<tr>
							<th>Explanation</th>
							<td>
								If the Data Type wants to include something in the Options column, it must return the HTML via this function. If 
								this function isn't defined (or it returns an empty string), the string "No options available." will be outputted in 
								the cell. This is used for inserting static content into the appropriate spot in the table; if the Data Type needs 
								something more dynamic, it should subscribe to the appropriate event.
							</td>
						</tr>
					</tbody>
				</table>


				<h3>getHelpHTML()</h3>

				<table class="table">
					<tbody>
						<tr>
							<th>Req/Opt</th>
							<td><span class="label label-info">optional</span></td>
						</tr>
						<tr>
							<th>Params</th>
							<td>None</td>
						</tr>
						<tr>
							<th>Explanation</th>
							<td>
								Returns the help content for this Data Type (HTML / string).
							</td>
						</tr>
					</tbody>
				</table>


				<h3>getRowGenerationOptions()</h3>

				<table class="table">
					<tbody>
						<tr>
							<th>Req/Opt</th>
							<td><span class="label label-info">optional</span></td>
						</tr>
						<tr>
							<th>Params</th>
							<td>
								<ol>
									<li><b>$generator</b> (object): the instance of the <code>Generator</code>object, containing assorted public methods</li>
									<li><b>$post</b> (array): the entire contents of $_POST</li>
									<li><b>$colNum</b> (integer): the column number (<i>row</i> in the UI...!) of the item</li>
									<li><b>$numCols</b> (integer): the number of columns in the data set</li>
								</ol>
							</td>
						</tr>
						<tr>
							<th>Returns</th>
							<td>
								<ul>
									<li>false, if the Data Type doesn't have sufficient information to generate the row (i.e. things 
										weren't filled in in the UI and the Data Type didn't add proper validation)</li>
									<li>anything else. This can be any data structure needed by the Data Type. It'll be passed as-is
										into the generateItem function as the second parameter.</li>
								</ul>
							</td>
						</tr>
						<tr>
							<th>Explanation</th>
							<td>
								Called during data generation. This determines what options the user selected in the user interface; it's
								used to figure out what settings to pass to each Data Type to provide that function the information needed
								to generate that particular data item. Note: if this function determines that the values entered by the 
								user in the options column are invalid (most likely just incomplete) the function can explicitly return 
								false to tell the core script to ignore this row.
							</td>
						</tr>
					</tbody>
				</table>


				<h3>getDataTypeMetadata()</h3>

				<table class="table">
					<tbody>
						<tr>
							<th>Req/Opt</th>
							<td><span class="label label-info">optional</span></td>
						</tr>
						<tr>
							<th>Returns</th>
							<td>array</td>
						</tr>
						<tr>
							<th>Explanation</th>
							<td>
								Used for providing additional metadata about the Data Type for use during generation. Right now this
								is only used to pass additional data to the SQL Export Type so it can intelligently create a CREATE TABLE
								statement with database column types and sizes that are appropriate to each field type.
							</td>
						</tr>
					</tbody>
				</table>
			</section>

			<section id="phpClassNonOverridableMethods">
				<h2>Non-overridable Methods</h2>
				<p>
					The following methods are defined on the Data Plugin abstract class, for use when developing a Data Type.
				</p>

				<table class="table table-striped">
					<thead>
						<tr>
							<th>Function</th>
							<th>Explanation</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>getName()</td>
							<td>returns the Data Type name.</td>
						</tr>
						<tr>
							<td>getIncludedFiles()</td>
							<td>returns list (array) of included files.</td>
						</tr>
						<tr>
							<td>getDataTypeFieldGroup()</td>
							<td>returns the field type group to which this Data Type belongs.</td>
						</tr>
						<tr>
							<td>getDataTypeFieldGroupOrder()</td>
							<td>returns the order of the field type group.</td>
						</tr>
						<tr>
							<td>getProcessOrder()</td>
							<td>returns the Data Type process order.</td>
						</tr>
						<tr>
							<td>getPath()</td>
							<td>returns the path to the Data Type file.</td>
						</tr>
						<tr>
							<td>getJSModules()</td>
							<td>returns the array of JS modules.</td>
						</tr>
						<tr>
							<td>getCSSFiles()</td>
							<td>returns the array of CSS files for the Data Type.</td>
						</tr>
						<tr>
							<td>isEnabled()</td>
							<td>returns whether or not the Data Type is enabled or not.</td>
						</tr>
					</tbody>
				</table>
			</section>

			<hr />

			<section id="jsModule">
				<h2>The JS Module</h2>
				<p>
					Each Data Type may choose to have an optional JS component: a javascript module that performs certain functionality 
					like saving/loading the data type data, running client-side validation on the user inputs (if required) and 
					triggering whatever additional JS code is necessary.
				</p>

				<h4>Optional or required?</h4>

				<p>
					The JS module is optional. The Core script handles saving and loading the Column Title and Data Type for all Data 
					Types, so if you don't need anything in the Example or Options columns, you don't need to include a JS module.
				</p>

				<p>
					Explaining how the JS module works can be a little abstract, so let's start with an example. 
				</p>
			</section>

			<section id="jsModuleExample">
				<h3>Example: Alphanumeric Data Type</h3>

				<p>
					The following is the JS module for the <code>Alphanumeric</code> Data Type. Give it a look over, then we'll
					pull it apart and explain each bit below.
				</p>
<pre class="prettyprint linenums">
/*global $:false*/
define([
	"manager",
	"constants",
	"lang",
	"generator"
], function(manager, C, L, generator) {

	"use strict";

	/**
	 * @name AlphaNumeric
	 * @description JS code for the AlphaNumeric Data Type.
	 * @see DataType
	 * @namespace
	 */

	var MODULE_ID = "data-type-AlphaNumeric";
	var LANG = L.dataTypePlugins.AlphaNumeric;
	var subscriptions = {};

	var _init = function() {
		subscriptions[C.EVENT.DATA_TABLE.ROW.EXAMPLE_CHANGE + "__" + MODULE_ID] = _exampleChange;
		manager.subscribe(MODULE_ID, subscriptions);
	};

	var _saveRow = function(rowNum) {
		return {
			"example": $("#dtExample_" + rowNum).val(),
			"option":  $("#dtOption_" + rowNum).val()
		};
	};

	var _loadRow = function(rowNum, data) {
		return {
			execute: function() {
				$("#dtExample_" + rowNum).val(data.example);
				$("#dtOption_" + rowNum).val(data.option);
			},
			isComplete: function() { return $("#dtOption_" + rowNum).length > 0; }
		};
	};

	var _exampleChange = function(msg) {
		$("#dtOption_" + msg.rowID).val(msg.value);
	};

	var _validate = function(rows) {
		var visibleProblemRows = [];
		var problemFields      = [];
		for (var i=0; i&lt;rows.length; i++) {
			var currEl = $("#dtOption_" + rows[i]);
			if ($.trim(currEl.val()) === "") {
				var visibleRowNum = generator.getVisibleRowOrderByRowNum(rows[i]);
				visibleProblemRows.push(visibleRowNum);
				problemFields.push(currEl);
			}
		}
		var errors = [];
		if (visibleProblemRows.length) {
			errors.push({ els: problemFields, error: LANG.incomplete_fields + " &lt;b&gt;" + visibleProblemRows.join(", ") + "&lt;/b&gt;"});
		}
		return errors;
	};

	manager.registerDataType(MODULE_ID, {
		init: _init,
		validate: _validate,
		saveRow: _saveRow,
		loadRow: _loadRow
	});
});
</pre>

				<p>
					Now let's go line by line.
				</p>

				<ul>
					<li><code>/*global $:false*/</code> this first line is for jshint/jslint. In my local environment, I use jshint with strict mode
						to catch problems. This line just tells the interpreter to ignore the dollar sign. It's a global, used by jQuery.</li>
					<li>
<pre class="prettyprint">define([
	"manager",
	"constants",
	"lang",
	"generator"
], function(manager, C, L, generator) {
	//...
});
</pre>
						<p>
							The outer code that wraps the entire JS module is called within requireJS's <core>define</code> function. This ensures
							the code is defined as an AMD (Asynchronous Module Definition) for consumption by other code. The important thing 
							to understand here is the parameters. The first array params define string labels to other modules: they all map to
							specific JS files - you can find the mapping in <code>/resources/scripts/requireConfig.js</code>. Each of those 
							discrete modules is in turn passed to the Data Type module via functions in the anonymous section param to define(). 
							Whatever public API those modules reveal are now accessible via the four params: <code>manager</code>, <code>constants</code>,
							<code>lang</code>, <code>generator</code>.
						</p>

						<p>
							When defining your own Data Type module JS file, you'll want to include all four of those params. They all contain
							useful functionality and data that you'll need. 
						</p>
					</li>
					<li><code>"use strict";</code> - do it! JS strict mode is never a bad idea. :D</li>
					<li>Here we're going to skip ahead to the very end of the code, to these lines:

<pre class="prettyprint">
	manager.registerDataType(MODULE_ID, {
		init: _init,
		validate: _validate,
		saveRow: _saveRow,
		loadRow: _loadRow
	});
</pre>
						<p>
							This chunk of code is <b>required</b> for your Data Type. What it does is register your Data Type with the core. That 
							allows it to listen to published events, publish its own events for other code to listen to, tie into the validation
							functionality and so on. It's pretty straightforward. The <code>manager.registerDataType()</code> function takes
							two parameters: the unique MODULE_ID constant, defined above (see below) and an object containing certain required
							and optional functions, whose property names have special values. Again, more on that below. Now let's go back to the 
							top of the code again. 
						</p>
					</li>
					<li>

<pre class="prettyprint">
	/**
	 * @name AlphaNumeric
	 * @description JS code for the AlphaNumeric Data Type.
	 * @see DataType
	 * @namespace
	 */

	var MODULE_ID = "data-type-AlphaNumeric";
	var LANG = L.dataTypePlugins.AlphaNumeric;
</pre>
						<ul>
							<li>
								The comment is of a particular format for being understood by JSDoc. For more information 
								on that, see the <a href="http://code.google.com/p/jsdoc-toolkit/" target="_blank">JS Doc project</a>.
							</li>
							<li>
								The <code>MODULE_ID</code> variable is special. It must <i>always</i> be of the form <b>data-type-[FOLDER NAME]</b>.
								That acts a unique identifier within the client-side code so the Manager can keep track of who's who.
							</li>
							<li>
								As with the PHP code, the language strings for your Data Type are automatically accessible: you don't have to do 
								any extra work to get access to them. The <code>L</code> function param fed to your Data Type contains all language
								strings in the system - in whatever language is currently selected. To locate the strings for your own module, 
								just reference it by your Data Type folder name, again: <code>L.dataTypePlugins.[FOLDER NAME]</code>
							</li>
						</ul>
					</li>
					<li>
						The following lines all define special functions. Rather than explain the implementation details of each of these for the 
						Alphanumeric type, we'll discuss these in a more abstract sense in the next section.
					</li>
				</ul>
			</section>

			<section id="jsModuleFunctions">
				<h3>Registration Functions</h3>

				<p>
					As explained above, the second parameter of the <code>manager.registerDataType()</code> function is an object 
					containing various predefined functions. This explains what are the properties for that object and what they're used 
					for. Note: <i>all properties are optional</i>, but you'll almost certainly need one or more.
				</p>

				<table class="table table-striped">
					<thead>
						<tr>
							<th>Property</th>
							<th width="100">Params</th>
							<th>Returns</th>
							<th>Explanation</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>init</td>
							<td>&#8212;</td>
							<td>&#8212;</td>
							<td>If this is defined for your Data Type, it gets called on page load prior to any events being published. By "event"
								I mean a custom published event, which I'll explain more thoroughly in the <a href="#jsModulePubSub">Pub/Sub</a>
								section below.
							</td>
						</tr>
						<tr>
							<td>run</td>
							<td>&#8212;</td>
							<td>&#8212;</td>
							<td>
								The run() function gets called for all Data Types and Export Types after their init()'s are called. As such, 
								run() can rely on all subscriptions being in place so events published at this juncture will have an 
								audience. 
							</td>
						</tr>
						<tr>
							<td>saveRow</td>
							<td>rowNum <span class="label label-success">int<span></th>
							<td><span class="label label-info">object</span></td>
							<td>
								When the user saves a Data Set, the Data Generator examines the table and calls the appropriate Data Type's
								saveRow() method. This method is responsible for determining what information it wants to save for the row. 
								Generally all it does is examine the DOM and extract whatever values the user entered in custom fields 
								that the Data Type field uses. It then returns an object of simple property-value pairs. The row number being 
								passed to this function is the unique row number for the row - it may <i>not</i> be the visual row number seen 
								in the UI. After a row is created, it can be re-ordered. The row number passed to this function can be used 
								for DOM element identification.
							</td>
						</tr>
						<tr>
							<td>loadRow</td>
							<td>
								rowNum <span class="label label-success">int</span><br />
								data <span class="label label-info">object</span>
							</td>
							<td>&#8212;</td>
							<td>
								When the user loads a saved data set, the script calls each Data Type's loadRow() function, passing the appropriate
								row number and whatever data was originally returned by its saveRow() function. The row number should be sufficient
								information to identify the appropriate elements in the DOM and re-enter the saved information.
							</td>
						</tr>
						<tr>
							<td>validate</td>
							<td>rows <span class="label label-inverse">array</span></td>
							<td>
								<span class="label label-inverse">array</span>
							</td>
							<td>
								<p>
									When the user clicks on the Generate button, the core first validates the information they've entered. If a Data 
									Type defines this function, it means they want to confirm the user input for one or more of their custom fields - 
									mostly likely appearing in the Options column. The <b>rows</b> parameter is an array of row numbers that have this 
									Data Set selected. As mentioned above, the row numbers may not be the <i>visual</i> row numbers, because rows 
									may have been added / removed / resorted. However, it can be used to identify the appropriate DOM elements.
								</p>
								<p>
									This function needs to return an array of errors to display - or an empty array if there are no errors. Each 
									array index is an object of the following form: <code> { els: [], error: "error message here" }</code>. <b>els</b>
									is an array of DOM elements that have problems with them; <b>error</b> is the error message that will be displayed.
								</p>
								<p>
									Check out the Alphanumeric Data Type's validate() function above for an example of how this function can work.
								</p>
							</td>
						</tr>
					</tbody>
				</table>

			</section>

			<section id="jsModulePubSub">
				<h3>Pub/Sub &amp; Event List</h3>

				<p>
					As mentioned elsewhere, the client-side code revolves around the idea of publish/subscribe - or pub/sub. Different parts of 
					the script can publish arbitrary events with arbitrary information associated with them, and any module can choose to listen
					out for particular events and run code when they occur. This is a very elegant pattern: it allow us to keep our modules 
					loosely coupled and reduce the likelihood of introducing dependencies that can break things. 
				</p>

				<p>
					The core script publishes the following script for certain events that occur in the lifetime of the page. They're all 
					found in <code>/resources/scripts/constants.php</code> (returned as JS). You can refer to them in your code via the 
					<code>C</code> parameter, mapping to the <code>constants</code> module. The names are pretty descriptive so I won't 
					bother explaining them any further.
				</p>

				<ul>
					<li><code>C.EVENT.RESULT_TYPE.CHANGE</code></li>
					<li><code>C.EVENT.COUNTRIES.CHANGE</code></li>
					<li><code>C.EVENT.DATA_TABLE.ONLOAD_READY</code></li>
					<li><code>C.EVENT.DATA_TABLE.ROW.CHECK_TO_DELETE</code></li>
					<li><code>C.EVENT.DATA_TABLE.ROW.UNCHECK_TO_DELETE</code></li>
					<li><code>C.EVENT.DATA_TABLE.ROW.DELETE</code></li>
					<li><code>C.EVENT.DATA_TABLE.ROW.TYPE_CHANGE</code></li>
					<li><code>C.EVENT.DATA_TABLE.ROW.EXAMPLE_CHANGE</code></li>
					<li><code>C.EVENT.DATA_TABLE.ROW.ADD</code></li>
					<li><code>C.EVENT.DATA_TABLE.ROW.RE_SORT</code></li>
					<li><code>C.EVENT.DATA_TABLE.ROW.HELP_DIALOG_OPEN</code></li>
					<li><code>C.EVENT.DATA_TABLE.ROW.HELP_DIALOG_CLOSE</code></li>
					<li><code>C.EVENT.DATA_TABLE.CLEAR</code></li>
					<li><code>C.EVENT.GENERATE</code></li>
					<li><code>C.EVENT.IO.SAVE</code></li>
					<li><code>C.EVENT.IO.LOAD</code></li>
					<li><code>C.EVENT.TAB.CHANGE</code></li>
					<li><code>C.EVENT.MODULE.REGISTER</code></li>
					<li><code>C.EVENT.MODULE.UNREGISTER</code></li>
				</ul>

				<h4>How to subscribe to an event</h4>

				<p>
					Generally you'll want to set up your subscriptions in your module's <b>init()</b> function. Here's how it works:
				</p>

<pre class="prettyprint">
...

var _init = function() {
	var subscriptions = {};
	subscriptions[C.EVENT.COUNTRIES.CHANGE] = _onChangeCountries;
	manager.subscribe(subscriptions);
};

var _onChangeCountries = function(msg) {
	console.log(msg);
};

...

manager.registerDataType(MODULE_ID, {
	init: _init
});

...
</pre>

				<p>
					That would subscribe to the <code>C.EVENT.COUNTRIES.CHANGE</code> event (which is where the user adds/removes a country 
					from the Country List section in the UI) and attaches a callback function - <code>_onChangeCountries()</code>. The manager.subscribe()
					function can be called at any time in any of your functions, so you can subscribe to events on the fly.
				</p>
			</section>

			<section id="practicalTips">
				<h2>Practical Tips</h2>
				<p>
					I thought maybe I'd include this section on how to achieve a few practical things. <a href="contribute.php">Let me know</a> if you're 
					stuck on something and maybe I'll expand this section to explain how to do it.
				</p>
			</section>


			<section id="populatingCols">
				<h3>Populating "Example" and "Options" columns</h2>

				<p>
					If your Data Type is non-trivial, you'll probably want to include some custom HTML to appear in the Example and Options columns
					in the generator table. Here's how that works.
				</p>

				<p>
					First, your PHP class above needs to define the <code>getExampleColumnHTML()</code> and <code>getOptionsColumnHTML()</code> 
					methods. They should return a block of generic markup that the client-side Core code will automatically insert into any 
					row where the user selects your Data Type. Since that same block will be inserted for <i>every</i> row of your Data Type, 
					for anything you need to be unique - e.g. input field names and IDs, include the <code>%ROW%</code> placeholder. When 
					the HTML is inserted into the appropriate locations in the DOM, those placeholders will be replaced by the appropriate
					row number, thus allowing you to uniquely pinpoint those fields.
				</p>
				<p>
				</p>

			</section>


			<section id="availableResources">
				<h2>Available Resources</h2>
				<p>
					There are several client-side code libraries already available in the page that can be used in your Data Type:
				</p>
				<ul>
					<li>jQuery ($)</li>
					<li>jQuery UI</li>
					<li><a href="http://momentjs.com/" target="_blank">MomentJS</a>- date/time formatting script</li>
					<li><a href="http://harvesthq.github.io/chosen/" target="_blank">Chosen</a> - dropdown enhancement</li>
				</ul>

				<p>
					You can always include additional libraries should you wish, but do try to namespace them.
				</p>
			</section>

			<section id="updatingUI">
				<h2>Adding your Data Type</h2>
				<p>
					When you add a new Data Type, just creating the new files and folders won't get it to show up in the UI. First,
					you'll need to follow the steps below to make sure your PHP class and JS Module has been created properly, and afterwards
					you'll need to refresh the UI.
				</p>
				<p>
					To update the list of available Data Types in the UI, go to the second <code>Settings</code> tab. There, click the 
					<code>Reset Plugins</code> button. A dialog will appears which resets all the available plugins (don't worry, this 
					won't cause any problems with saved content or anything like that). After refreshing the page, you should see
					your Data Type appear in the Data Type dropdowns in the generator.
				</p>
			</section>

			<section id="contribute">
				<h2>How to Contribute</h2>
				<p>
					If you feel that your Data Type could be of use to other people, send it our way! I'd love to take a look at it,
					and maybe even include it in the core script for others to download. Read the <a href="contribute.php">How to Contribute</a>
					page.
				</p>
			</section>

		</div>
	</div>
</div>

<?php
require_once("templates/footer.php");
?>