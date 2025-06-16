<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KEBENET Dapo Cutlist Calculator - Templates & Groups</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="crud.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f6;
            color: #333;
        }
        .primary-button {
            background-color: #2e9578;
            border-color: transparent;
            color: white;
        }
        .primary-button:hover {
            background-color: #267d65;
            color: white;
        }
        .primary-button:disabled {
            background-color: #72bca5;
            cursor: not-allowed;
        }
        .danger-button {
            background-color: #f14668;
            border-color: transparent;
            color: white;
        }
        .danger-button:hover {
            background-color: #ee3058;
            color: white;
        }
        .info-button {
            background-color: #3e8ed0;
            border-color: transparent;
            color: white;
        }
        .info-button:hover {
            background-color: #307bbe;
            color: white;
        }
        .warning-button {
            background-color: #ffdd57;
            border-color: transparent;
            color: rgba(0,0,0,0.7);
        }
        .warning-button:hover{
            background-color: #ffce24;
            color: rgba(0,0,0,0.7);
        }

        .primary-text { color: #2e9578; }
        .box, .card {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .card-header-title { color: #236e5a; }
        .card-header { background-color: #e9f4f1; }
        .table th, .table td { vertical-align: middle; }
        .table thead th { background-color: #e9f4f1; color: #236e5a; }
        .input, .select select, .textarea { border-radius: 6px; }
        .input:focus, .select select:focus, .textarea:focus {
            border-color: #2e9578;
            box-shadow: 0 0 0 0.125em rgba(46, 149, 120, 0.25);
        }
        .section-title {
            border-bottom: 2px solid #2e9578;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .notification .delete { right: 0.5rem; top: 0.5rem; }
        .notification { padding-right: 2.5rem; }
        .table td .input { min-width: 80px; font-size: 0.9em; }
        .table td .button { padding-left: 0.75em; padding-right: 0.75em; }
        .gemini-output {
            background-color: #e9f4f1; border-left: 4px solid #2e9578;
            padding: 1rem; margin-top: 1rem; border-radius: 4px;
            white-space: pre-wrap; font-family: monospace;
            max-height: 300px; overflow-y: auto;
        }
        .loading-spinner {
            display: inline-block; width: 1em; height: 1em;
            border: 2px solid rgba(46,149,120,0.3); border-radius: 50%;
            border-top-color: #2e9578; animation: spin 1s ease-in-out infinite;
            margin-left: 0.5em;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .group-card, .template-card { margin-bottom: 2rem; }
        .card-header-icon.button { margin-left: 0.5rem; }
        .is-sticky-header { position: sticky; top: 0; z-index: 10; }
    </style>
</head>
<body>
    <section class="section">
        <div class="container" x-data="cutlistCalculator()">
            <h1 class="title has-text-centered is-2 primary-text">KEBENET Cutlist Calculator</h1>
            <p class="subtitle has-text-centered is-5">Define KEBENET groups with custom dimensions & formulas!</p>

            <div class="tabs is-centered is-boxed is-medium">
                <ul>
                    <li :class="{ 'is-active': activeTab === 'group' }" @click="activeTab = 'group'">
                        <a>
                            <span class="icon is-small"><i class="fas fa-cubes" aria-hidden="true"></i></span>
                            <span>Groups</span>
                        </a>
                    </li>
                    <li :class="{ 'is-active': activeTab === 'template' }" @click="activeTab = 'template'">
                        <a>
                            <span class="icon is-small"><i class="fas fa-file-alt" aria-hidden="true"></i></span>
                            <span>Templates</span>
                        </a>
                    </li>
                    <li :class="{ 'is-active': activeTab === 'config' }" @click="activeTab = 'config'">
                        <a>
                            <span class="icon is-small"><i class="fas fa-cogs" aria-hidden="true"></i></span>
                            <span>Config</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="tab-content">
                <div x-show="activeTab === 'config'" x-transition>
                    <div class="box mb-5">
                        <h2 class="title is-4 section-title primary-text">Configuration</h2>
                        <div class="columns is-multiline">
                            <div class="column is-one-third">
                                <div class="field">
                                    <label class="label" for="globalSheetThickness">Sheet Thickness</label>
                                    <div class="control has-icons-left">
                                        <input class="input" type="number" step="0.001" id="globalSheetThickness" x-model.number="globalConfig.sheetThickness" placeholder="e.g., 0.75">
                                        <span class="icon is-small is-left"><i class="fas fa-layer-group"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-one-third">
                                <div class="field">
                                    <label class="label" for="globalSheetWidth">Sheet Width</label>
                                    <div class="control has-icons-left">
                                        <input class="input" type="number" id="globalSheetWidth" x-model.number="globalConfig.sheetWidth" placeholder="e.g., 48">
                                        <span class="icon is-small is-left"><i class="fas fa-ruler-combined"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-one-third">
                                <div class="field">
                                    <label class="label" for="globalSheetHeight">Sheet Height</label>
                                    <div class="control has-icons-left">
                                        <input class="input" type="number" id="globalSheetHeight" x-model.number="globalConfig.sheetHeight" placeholder="e.g., 96">
                                        <span class="icon is-small is-left"><i class="fas fa-ruler-combined"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-one-third">
                                <div class="field">
                                    <label class="label" for="globalDoorGap">Door Gap - Side</label>
                                    <div class="control has-icons-left">
                                        <input class="input" type="number" step="0.001" id="globalDoorGap" x-model.number="globalConfig.doorGap" placeholder="e.g., 0.25">
                                        <span class="icon is-small is-left"><i class="fas fa-door-open"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-one-third">
                                <div class="field">
                                    <label class="label" for="globalDoorGapTop">Door Gap - Top</label>
                                    <div class="control has-icons-left">
                                        <input class="input" type="number" step="0.001" id="globalDoorGapTop" x-model.number="globalConfig.doorGapTop" placeholder="e.g., 0.375">
                                        <span class="icon is-small is-left"><i class="fas fa-compress-alt"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'template'" x-transition>
                    <div class="box mb-6">
                        <h2 class="title is-3 section-title primary-text">Part Templates</h2>
                        <template x-for="(template, templateIndex) in partDefinitionTemplates" :key="template.id">
                            <div class="card template-card">
                                <header class="card-header">
                                    <div class="card-header-title field is-expanded mb-0">
                                        <label :for="'templateName-' + template.id" class="label mr-2 mb-0 is-align-self-center">Name</label>
                                        <div class="control is-expanded">
                                            <input class="input is-small" :id="'templateName-' + template.id" type="text" x-model="template.name" placeholder="e.g., Standard Base KEBENET">
                                        </div>
                                    </div>
                                    
                                    <a :href="'template-form.php?id=' + template.id" class="button is-small info-button mr-2" title="Edit Template">
                                        <span class="icon is-small"><i class="fas fa-edit"></i></span>
                                    </a>
                                    <button class="button is-small danger-button card-header-icon" @click="removeTemplate(template.id)" title="Delete Template">
                                        <span class="icon is-small"><i class="fas fa-trash"></i></span>
                                    </button>
                                    
                                </header>
                                <div class="card-content">
                                    <h4 class="title is-6">Parts</h4>
                                    <div class="table-container">
                                        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                                            <thead>
                                                <tr><th>Name</th><th>Width</th><th>Height</th><th>Qty</th><th>Action</th></tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="(partDef, partDefIndex) in template.partDefinitions" :key="partDef.id">
                                                    <tr>
                                                        <td><input class="input is-small" type="text" x-model="partDef.name"></td>
                                                        <td><input class="input is-small" type="text" x-model="partDef.widthStr"></td>
                                                        <td><input class="input is-small" type="text" x-model="partDef.heightStr"></td>
                                                        <td><input class="input is-small" type="text" x-model="partDef.quantityStr"></td>
                                                        <td>
                                                            <button class="button is-small danger-button" @click="removePartFromTemplate(template.id, partDef.id)">
                                                                <span class="icon is-small"><i class="fas fa-times"></i></span>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3 has-text-right">
                                        <button class="button is-small primary-button" @click="addPartToTemplate(template.id)">
                                            <span class="icon"><i class="fas fa-plus"></i></span>
                                            <span>Add New Part</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div class="mt-4 has-text-centered">
                            <button class="button primary-button is-medium" @click="addTemplate()">
                                <span class="icon"><i class="fas fa-file-medical"></i></span>
                                <span>Add New Template</span>
                            </button>
                            <a href="template-form.php" class="button primary-button is-medium">
                                <span class="icon"><i class="fas fa-file-medical"></i></span>
                                <span>Add New Template Database</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'group'" x-transition>
                    <h2 class="title is-3 section-title primary-text">Groups</h2>
                    <div class="box mb-5"> <div class="field is-horizontal mb-4">
                            <div class="field-label is-normal">
                                <label class="label">Add From Template:</label>
                            </div>
                            <div class="field-body">
                                <div class="field has-addons">
                                    <div class="control is-expanded">
                                        <div class="select is-fullwidth">
                                            <select x-model="selectedTemplateIdForNewGroup">
                                                <option value="">Select Template...</option>
                                                <template x-for="template in partDefinitionTemplates" :key="template.id">
                                                    <option :value="template.id" x-text="template.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control">
                                        <button class="button primary-button" @click="addKebenetGroup()">
                                            <span class="icon"><i class="fas fa-plus-circle"></i></span>
                                            <span>New Group</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <template x-for="(group, groupIndex) in kebenetGroups" :key="group.id">
                        <div class="card group-card">
                            <header class="card-header">
                                <div class="card-header-title field is-expanded mb-0">
                                    <label :for="'groupName-' + group.id" class="label mr-2 mb-0 is-align-self-center">Name</label>
                                    <div class="control is-expanded">
                                        <input class="input is-small" :id="'groupName-' + group.id" type="text" x-model="group.groupName" placeholder="e.g., Base KEBENETs">
                                    </div>
                                </div>
                                <button class="button is-small info-button card-header-icon" @click="cloneKebenetGroup(group.id)" aria-label="Clone Group" title="Clone Group">
                                    <span class="icon is-small"><i class="fas fa-clone"></i></span>
                                </button>
                                <button class="button is-small danger-button card-header-icon" @click="removeKebenetGroup(group.id)" aria-label="Remove Group" title="Remove Group">
                                    <span class="icon is-small"><i class="fas fa-trash-alt"></i></span>
                                </button>
                            </header>
                            <div class="card-content">
                                <h3 class="title is-5 primary-text">Dimensions</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-half">
                                        <div class="field">
                                            <label class="label" :for="'groupWidths-' + group.id">Widths space-separated</label>
                                            <input class="input" :id="'groupWidths-' + group.id" type="text" x-model="group.dimensions.widthString" placeholder="e.g., 24 30 36">
                                        </div>
                                    </div>
                                    <div class="column is-one-quarter">
                                        <div class="field">
                                            <label class="label" :for="'groupHeight-' + group.id">Height</label>
                                            <input class="input" :id="'groupHeight-' + group.id" type="number" x-model.number="group.dimensions.height">
                                        </div>
                                    </div>
                                    <div class="column is-one-quarter">
                                        <div class="field">
                                            <label class="label" :for="'groupDepth-' + group.id">Depth</label>
                                            <input class="input" :id="'groupDepth-' + group.id" type="number" x-model.number="group.dimensions.depth">
                                        </div>
                                    </div>
                                    <div class="column is-one-quarter">
                                        <div class="field">
                                            <label class="label" :for="'groupDoorCount-' + group.id">Doors</label>
                                            <input class="input" :id="'groupDoorCount-' + group.id" type="number" x-model.number="group.groupSpecificConfig.doorCount" min="0">
                                        </div>
                                    </div>
                                </div>

                                <h3 class="title is-5 primary-text mt-4">Part Definitions (<span x-text="group.sourceTemplateName ? 'From: ' + group.sourceTemplateName : 'Custom'"></span>)</h3>
                                <div class="table-container">
                                    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                                        <thead>
                                            <tr><th>Name</th><th>Width</th><th>Height</th><th>Quantity</th><th>Actions</th></tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="(partDef, partDefIndex) in group.partDefinitions" :key="partDef.id">
                                                <tr>
                                                    <td><input class="input is-small" type="text" x-model="partDef.name"></td>
                                                    <td><input class="input is-small" type="text" x-model="partDef.widthStr"></td>
                                                    <td><input class="input is-small" type="text" x-model="partDef.heightStr"></td>
                                                    <td><input class="input is-small" type="text" x-model="partDef.quantityStr"></td>
                                                    <td>
                                                        <button class="button is-small danger-button" @click="removePartDefinitionFromGroup(group.id, partDef.id)">
                                                            <span class="icon is-small"><i class="fas fa-times"></i></span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3 has-text-right">
                                    <button class="button is-small primary-button" @click="addPartDefinitionToGroup(group.id)">
                                        <span class="icon"><i class="fas fa-plus"></i></span>
                                        <span>Add Custom Part</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div class="has-text-centered my-6" x-show="kebenetGroups.length > 0">
                        <button class="button is-large primary-button" @click="generateCutlist()" :disabled="kebenetGroups.length === 0">
                            <span class="icon"><i class="fas fa-cogs"></i></span>
                            <span>Generate Cutlist</span>
                        </button>
                    </div>

                    <div class="box mt-5" x-show="resultRows.length > 0 || errorMessage || infoMessage || isLoadingOptimizationTips || isLoadingProjectSummary">
                        <h2 class="title is-4 section-title primary-text">Final Aggregated Cutlist</h2>
                        <div class="notification is-info is-light mt-4" x-show="infoMessage" x-transition>
                            <button class="delete" @click="infoMessage = ''"></button>
                            <span x-html="infoMessage"></span>
                        </div>
                        <div class="notification is-warning is-light mt-4" x-show="errorMessage" x-transition>
                            <button class="delete" @click="errorMessage = ''"></button>
                            <span x-html="errorMessage"></span>
                        </div>
                        <div class="table-container" x-show="resultRows.length > 0">
                            <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Width</th>
                                        <th>Height</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(row, index) in resultRows" :key="index">
                                        <tr>
                                            <td x-text="row.name"></td>
                                            <td x-text="row.calculatedWidth"></td>
                                            <td x-text="row.calculatedHeight"></td>
                                            <td x-text="row.calculatedQuantity"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <div x-show="resultRows.length > 0 && !errorMessage.includes('No valid KEBENET widths')" class="mt-5">
                             <div class="field is-grouped is-grouped-centered">
                                 <p class="control">
                                     <button class="button primary-button" @click="getOptimizationTips()" :disabled="isLoadingOptimizationTips || isLoadingProjectSummary">
                                         <span class="icon"><i class="fas fa-magic"></i></span>
                                         <span>✨ Get Optimization Tips</span>
                                         <span x-show="isLoadingOptimizationTips" class="loading-spinner"></span>
                                     </button>
                                 </p>
                                 <p class="control">
                                     <button class="button primary-button" @click="getProjectSummary()" :disabled="isLoadingOptimizationTips || isLoadingProjectSummary">
                                         <span class="icon"><i class="fas fa-file-alt"></i></span>
                                         <span>✨ Generate Project Summary</span>
                                         <span x-show="isLoadingProjectSummary" class="loading-spinner"></span>
                                     </button>
                                 </p>
                             </div>
                             <div x-show="optimizationTips" class="mt-4">
                                 <h3 class="title is-5 primary-text">Material Optimization Tips:</h3>
                                 <div class="gemini-output content" x-html="optimizationTips"></div> <button class="button is-small is-light mt-2" @click="optimizationTips = ''">Clear Tips</button>
                             </div>
                             <div x-show="projectSummary" class="mt-4">
                                 <h3 class="title is-5 primary-text">Project Summary:</h3>
                                 <div class="gemini-output content" x-html="projectSummary"></div> <button class="button is-small is-light mt-2" @click="projectSummary = ''">Clear Summary</button>
                             </div>
                              <div x-show="geminiError" class="notification is-danger is-light mt-4">
                                  <button class="delete" @click="geminiError = ''"></button>
                                  <strong class="primary-text">AI Assistant Error:</strong> <span x-html="geminiError"></span>
                              </div>
                         </div>
                    </div>
                </div>
            </div> </div> </section>

    <script>
    // --- Global Helper Functions ---
    const FORMULA_SANITIZATION_REGEX = /[^a-zA-Z0-9_()./*+\-%, ]/g;

    /**
     * Evaluates a mathematical formula string within a given scope.
     * @param {string} formulaStr - The formula string to evaluate.
     * @param {object} scope - An object containing variables available to the formula.
     * @returns {number|string} The result of the evaluation, or 'Error' if an error occurs.
     */
    function evaluateFormulaLogic(formulaStr, scope) {
        const sanitizedFormulaStr = String(formulaStr).replace(FORMULA_SANITIZATION_REGEX, '');
        if (sanitizedFormulaStr !== formulaStr) {
             console.warn("Original formula sanitized for evaluation:", formulaStr, "->", sanitizedFormulaStr);
        }
        try {
            // Using new Function() for dynamic evaluation.
            // Ensure that formulaStr comes from a trusted source or is adequately controlled,
            // as direct user input into new Function() can be a security risk if not managed.
            // For this tool, users define their own formulas for their own calculations,
            // so the risk is context-dependent and may be acceptable.
            const func = new Function(...Object.keys(scope), `return ${sanitizedFormulaStr};`);
            const result = func(...Object.values(scope));
            // Round to 3 decimal places if it's a float, otherwise return as is (e.g. integer)
            return (typeof result === 'number') ? (Number.isInteger(result) ? result : parseFloat(result.toFixed(3))) : result;
        } catch (e) {
            console.error("Error evaluating formula:", sanitizedFormulaStr, "(Original:", formulaStr, ")", "Error:", e.message, "Scope:", scope);
            return 'Error'; // Return a specific error indicator
        }
    }

    /**
     * Aggregates parts with the same name, width, and height, summing their quantities.
     * Sorts the aggregated parts by name, then width, then height.
     * @param {Array<object>} allCalculatedParts - Array of part objects {name, calculatedWidth, calculatedHeight, calculatedQuantity}.
     * @returns {Array<object>} Sorted array of aggregated part objects.
     */
    function aggregateAndSortPartsLogic(allCalculatedParts) {
        const aggregatedResults = {};
        allCalculatedParts.forEach(item => {
            // Ensure name is a string and trim it for consistent key generation
            const partName = String(item.name || 'Unnamed Part').trim();
            const key = `${partName}_${item.calculatedWidth}_${item.calculatedHeight}`;

            if (aggregatedResults[key]) {
                aggregatedResults[key].calculatedQuantity += item.calculatedQuantity;
            } else {
                // Store with the trimmed name
                aggregatedResults[key] = { ...item, name: partName };
            }
        });

        return Object.values(aggregatedResults).sort((a, b) => {
            const nameA = String(a.name || '').toLowerCase(); // Handle potential undefined names
            const nameB = String(b.name || '').toLowerCase();
            if (nameA < nameB) return -1;
            if (nameA > nameB) return 1;
            if (a.calculatedWidth < b.calculatedWidth) return -1;
            if (a.calculatedWidth > b.calculatedWidth) return 1;
            if (a.calculatedHeight < b.calculatedHeight) return -1;
            if (a.calculatedHeight > b.calculatedHeight) return 1;
            return 0;
        });
    }


    // --- Alpine.js Component ---
    function cutlistCalculator() {
        return {
            // --- State Properties ---
            activeTab: 'group',
            globalConfig: {
                sheetThickness: 0.75,
                sheetWidth: 48,
                sheetHeight: 96,
                doorGap: 0.25,      // Gap for side of door or between two doors
                doorGapTop: 0.5,  // Overall gap for top/bottom of door from KEBENET edge
            },
            partDefinitionTemplates: [],
            kebenetGroups: [],
            resultRows: [], // Stores the final aggregated and sorted cutlist

            selectedTemplateIdForNewGroup: "", // Bound to the select dropdown for choosing a template

            // UI Feedback State
            errorMessage: '',
            infoMessage: '',
            optimizationTips: '', // Stores AI-generated optimization tips
            isLoadingOptimizationTips: false,
            projectSummary: '', // Stores AI-generated project summary
            isLoadingProjectSummary: false,
            geminiError: '', // Stores errors from Gemini API calls
            apiKey: "", // IMPORTANT: User needs to fill this in for AI features

            // --- ID Counters (as part of component state to ensure uniqueness) ---
            nextTemplateId: 1,
            _globalNextTemplatePartDefId: 1, // Underscore indicates it's mainly for internal ID generation logic
            nextGroupId: 1,
            _globalNextGroupPartDefId: 1,    // Underscore indicates it's mainly for internal ID generation logic

            // --- Initialization ---
            init() {
                // Load some default templates and a group if none exist, for demo purposes
                if (this.partDefinitionTemplates.length === 0) { this.addDefaultTemplates(); }
                if (this.kebenetGroups.length === 0) { this.addKebenetGroup(); } // Add one default group
            },

            // --- ID Generation Helpers (Internal to Alpine component) ---
            _getNewTemplateId() { return this.nextTemplateId++; },
            _getNewTemplatePartDefId() { return this._globalNextTemplatePartDefId++; },
            _getNewGroupId() { return this.nextGroupId++; },
            _getNewGroupPartDefId() { return this._globalNextGroupPartDefId++; },

            // --- Template Management Methods ---
            addDefaultTemplates() {
                const basicTemplateParts = [
                    { id: this._getNewTemplatePartDefId(), name: 'Side', widthStr: 'depth', heightStr: 'height', quantityStr: '2' },
                    { id: this._getNewTemplatePartDefId(), name: 'Top/Bottom', widthStr: 'width - thick2', heightStr: 'depth', quantityStr: '2' },
                    { id: this._getNewTemplatePartDefId(), name: 'Shelf', widthStr: 'width - thick2', heightStr: 'depth - 1', quantityStr: 'Math.max(0, Math.floor(height / 12))' },
                    { id: this._getNewTemplatePartDefId(), name: 'Back Ply', widthStr: 'width', heightStr: 'height', quantityStr: '1' },
                    { id: this._getNewTemplatePartDefId(), name: 'Door', widthStr: 'Math.round((width / count) / 0.125) * 0.125 - doorgap', heightStr: 'height - door_gap_top', quantityStr: 'count' }
                ];
                this.partDefinitionTemplates.push({
                    id: this._getNewTemplateId(), name: 'Wall Unit',
                    partDefinitions: JSON.parse(JSON.stringify(basicTemplateParts)) // Deep clone
                });


                this.selectedTemplateIdForNewGroup = this.partDefinitionTemplates[0]?.id || ""; 
            },
            addTemplate() {
                this.partDefinitionTemplates.push({
                    id: this._getNewTemplateId(),
                    name: `New Template ${this.nextTemplateId -1}`, // Use current value before increment for naming
                    partDefinitions: [
                        {id: this._getNewTemplatePartDefId(), name: 'Sample Part', widthStr: 'width', heightStr: 'height', quantityStr: '1'}
                    ]
                });
            },
            removeTemplate(templateIdToRemove) {
                this.partDefinitionTemplates = this.partDefinitionTemplates.filter(t => t.id !== templateIdToRemove);
                // If the removed template was selected for new group creation, reset the selection
                if (this.selectedTemplateIdForNewGroup == templateIdToRemove) { // Use == for type coercion if needed, else ===
                    this.selectedTemplateIdForNewGroup = "";
                }
            },
            addPartToTemplate(templateId) {
                const template = this.partDefinitionTemplates.find(t => t.id === templateId);
                if (template) {
                    template.partDefinitions.push({
                        id: this._getNewTemplatePartDefId(),
                        name: '', widthStr: '', heightStr: '', quantityStr: '1'
                    });
                }
            },
            removePartFromTemplate(templateId, partDefIdToRemove) {
                const template = this.partDefinitionTemplates.find(t => t.id === templateId);
                if (template) {
                    template.partDefinitions = template.partDefinitions.filter(pd => pd.id !== partDefIdToRemove);
                }
            },

            // --- KEBENET Group Management Methods ---
            addKebenetGroup() {
                const newGroupId = this._getNewGroupId();
                let partsForNewGroup = [];
                let sourceTemplateName = "Custom"; // Default if no template selected or found

                if (this.selectedTemplateIdForNewGroup && String(this.selectedTemplateIdForNewGroup).trim() !== "") {
                    const templateIdToFind = Number(this.selectedTemplateIdForNewGroup); // Ensure it's a number for comparison
                    const template = this.partDefinitionTemplates.find(t => t.id === templateIdToFind);

                    if (template) {
                        sourceTemplateName = template.name;
                        // Deep clone part definitions and assign new unique IDs for this group instance
                        partsForNewGroup = template.partDefinitions.map(pd => ({
                            ...JSON.parse(JSON.stringify(pd)), // Deep clone the part definition object
                            id: this._getNewGroupPartDefId() // Assign a new unique ID for this instance
                        }));
                    } else {
                        console.warn(`Template with ID ${this.selectedTemplateIdForNewGroup} not found. Defaulting to custom parts for new group.`);
                    }
                }

                // If no template was used or template had no parts, add a default part
                if (partsForNewGroup.length === 0) {
                     partsForNewGroup.push({
                         id: this._getNewGroupPartDefId(),
                         name: 'Side Panel (Custom)',
                         widthStr: 'depth',
                         heightStr: 'height',
                         quantityStr: '2'
                    });
                }

                this.kebenetGroups.push({
                    id: newGroupId,
                    groupName: `KEBENET Group ${newGroupId}` + (sourceTemplateName !== "Custom" ? ` (from ${sourceTemplateName})` : ''),
                    dimensions: { widthString: '24', height: 34.5, depth: 11.5 }, // Default dimensions
                    groupSpecificConfig: { doorCount: (sourceTemplateName.toLowerCase().includes("door") ? 2 : 0) }, // Sensible default for door count based on template name
                    partDefinitions: partsForNewGroup,
                    sourceTemplateName: sourceTemplateName // Store the name of the template used
                });
            },
            removeKebenetGroup(groupIdToRemove) {
                this.kebenetGroups = this.kebenetGroups.filter(g => g.id !== groupIdToRemove);
                 if (this.kebenetGroups.length === 0) { // If all groups are removed, clear results
                    this.resultRows = []; this.infoMessage = 'All KEBENET groups removed.'; this.errorMessage = '';
                }
            },
            cloneKebenetGroup(groupIdToClone) {
                const originalGroup = this.kebenetGroups.find(g => g.id === groupIdToClone);
                if (!originalGroup) return;
                const clonedGroup = JSON.parse(JSON.stringify(originalGroup)); // Deep clone
                clonedGroup.id = this._getNewGroupId(); // Assign new unique ID to the cloned group
                clonedGroup.groupName = `${originalGroup.groupName} (Clone)`;
                // Assign new unique IDs to all part definitions within the cloned group
                clonedGroup.partDefinitions = clonedGroup.partDefinitions.map(pd => ({
                     ...pd, id: this._getNewGroupPartDefId()
                }));
                this.kebenetGroups.push(clonedGroup);
            },
            addPartDefinitionToGroup(groupId) { // For adding a custom part directly to a group
                const group = this.kebenetGroups.find(g => g.id === groupId);
                if (group) {
                    group.partDefinitions.push({
                        id: this._getNewGroupPartDefId(), // New unique ID for the part definition
                        name: '', widthStr: '', heightStr: '', quantityStr: '1'
                    });
                }
            },
            removePartDefinitionFromGroup(groupId, partDefIdToRemove) { // For removing a part from a specific group
                const group = this.kebenetGroups.find(g => g.id === groupId);
                if (group) {
                    group.partDefinitions = group.partDefinitions.filter(pd => pd.id !== partDefIdToRemove);
                }
            },

            // --- Calculation Engine Core (uses global helper) ---
            evalFormula(formulaStr, scope) {
                // Calls the globally defined helper function
                return evaluateFormulaLogic(formulaStr, scope);
            },

            // --- Main Calculation Orchestrator ---
            generateCutlist() {
                this._resetCalculationState(); // Clear previous results and messages
                if (this.kebenetGroups.length === 0) { this.errorMessage = "No KEBENET groups defined. Please add at least one group."; return; }

                const validationErrors = this._validateAllInputs();
                if (validationErrors.length > 0) {
                    this.errorMessage = validationErrors.join('<br>'); // Display validation errors
                    return;
                }

                // Process all groups and calculate parts
                const { allCalculatedParts, totalKebenetsProcessed, processingInfoMessages, processingErrorMessages } = this._processAllGroupsAndCalculateParts();

                // Aggregate and sort the calculated parts using the global helper
                this.resultRows = this._aggregateAndSortParts(allCalculatedParts);

                // --- Construct Feedback Messages ---
                let combinedInfoMessages = [...new Set(processingInfoMessages)]; // Start with unique processing info
                if (this.resultRows.length > 0 && processingErrorMessages.length === 0) {
                    const successMsg = `Cutlist successfully generated for ${totalKebenetsProcessed} KEBENET(s) across ${this.kebenetGroups.length} group(s).`;
                    combinedInfoMessages.push(successMsg);
                } else if (this.resultRows.length > 0 && processingErrorMessages.length > 0) {
                    const partialMsg = `Cutlist generated with some errors. Displaying successfully calculated parts. Please check error messages and console.`;
                    combinedInfoMessages.push(partialMsg);
                } else if (allCalculatedParts.length === 0 && processingErrorMessages.length === 0 && totalKebenetsProcessed > 0) {
                    combinedInfoMessages.push("No parts were generated (all quantities might be zero or formulas resulted in no parts).");
                } else if (allCalculatedParts.length === 0 && processingErrorMessages.length === 0 && totalKebenetsProcessed === 0 && this.kebenetGroups.length > 0) {
                     // Avoid redundant messages if specific "no widths" or "no parts" messages are already there
                     if (!combinedInfoMessages.some(msg => msg.includes("has no widths") || msg.includes("has no parts defined"))) {
                        combinedInfoMessages.push("No KEBENETs were processed. Ensure groups have valid widths and part definitions.");
                     }
                }

                if (combinedInfoMessages.length > 0) this.infoMessage = combinedInfoMessages.join('<br>');
                if (processingErrorMessages.length > 0) {
                     // Append to existing errors if any, or set new ones
                     this.errorMessage = (this.errorMessage ? this.errorMessage + '<br>' : '') + [...new Set(processingErrorMessages)].join('<br>');
                }
            },

            // --- Calculation Helper Methods (Internal to Alpine component) ---
            _resetCalculationState() {
                this.resultRows = []; this.errorMessage = ''; this.infoMessage = '';
                this.optimizationTips = ''; this.projectSummary = ''; this.geminiError = '';
            },
            _validateAllInputs() {
                const errors = [];
                // Validate global configuration
                if (this.globalConfig.sheetThickness <= 0) errors.push("Global Sheet Thickness must be a positive number.");
                if (this.globalConfig.sheetWidth <= 0) errors.push("Global Standard Sheet Width must be a positive number.");
                if (this.globalConfig.sheetHeight <= 0) errors.push("Global Standard Sheet Height must be a positive number.");
                // doorGap and doorGapTop can be 0, but not negative.
                if (this.globalConfig.doorGap < 0) errors.push("Global Door Gap (Side/Between) cannot be negative.");
                if (this.globalConfig.doorGapTop < 0) errors.push("Global Door Gap (Top/Overall) cannot be negative.");


                // Validate each KEBENET group
                this.kebenetGroups.forEach((group, groupIndex) => {
                    const groupLabel = `Group "${group.groupName || `Unnamed Group ${groupIndex + 1}`}"`;
                    if (!group.groupName.trim()) errors.push(`Group ${groupIndex + 1} requires a name.`);
                    if (group.dimensions.height <= 0) errors.push(`${groupLabel}: Height must be a positive number.`);
                    if (group.dimensions.depth <= 0) errors.push(`${groupLabel}: Depth must be a positive number.`);
                    if (group.groupSpecificConfig.doorCount < 0) errors.push(`${groupLabel}: Doors per KEBENET cannot be negative.`);

                    // Validate part definitions within the group
                    if (group.partDefinitions.length > 0) {
                        group.partDefinitions.forEach((pd, i) => {
                            const partLabel = `Part ${i+1} ("${pd.name || 'Unnamed Part'}") in ${groupLabel}`;
                            if (!pd.name.trim()) errors.push(`${partLabel}: Part name is required.`);
                            if (!pd.widthStr.trim()) errors.push(`${partLabel}: Width formula is required.`);
                            if (!pd.heightStr.trim()) errors.push(`${partLabel}: Height formula is required.`);
                            if (!pd.quantityStr.trim()) errors.push(`${partLabel}: Quantity formula is required.`);
                        });
                    } else {
                        // It's not necessarily an error to have no parts, but maybe a warning if widths are specified.
                        // For now, we let it proceed and it will result in no parts for that group.
                    }

                    // Validate widths in the group
                    const widthsRaw = group.dimensions.widthString.toString().trim().split(/\s+/).filter(w => w); // Filter out empty strings from multiple spaces
                    const widthsInGroup = widthsRaw.map(w => parseFloat(w)).filter(wNum => !isNaN(wNum) && wNum > 0);

                    if (widthsRaw.length > 0 && widthsInGroup.length !== widthsRaw.length) {
                        // Some widths were provided but were not valid positive numbers
                        errors.push(`${groupLabel}: Contains invalid or non-positive width values. Please use space-separated positive numbers.`);
                    }
                    // If widths were entered but none were valid, and the group has parts, it's an issue.
                    if (widthsInGroup.length === 0 && group.dimensions.widthString.trim() !== "" && group.partDefinitions.length > 0) {
                        errors.push(`${groupLabel}: No valid positive widths were found, but parts are defined. Please enter valid widths.`);
                    }
                });
                return errors;
            },
            _processAllGroupsAndCalculateParts() {
                let allCalculatedParts = [];
                let totalKebenetsProcessed = 0;
                let processingInfoMessages = []; // To collect informational messages during processing
                let processingErrorMessages = []; // To collect error messages during processing

                this.kebenetGroups.forEach((group) => {
                    const groupLabel = `Group "${group.groupName || 'Unnamed'}"`;
                    if (group.partDefinitions.length === 0) {
                        processingInfoMessages.push(`Info: ${groupLabel} has no part definitions defined and was skipped.`);
                        return; // Skip this group if it has no parts
                    }

                    // Parse and validate widths for the current group
                    const widthsInGroup = group.dimensions.widthString.toString().trim().split(/\s+/)
                                           .map(w => parseFloat(w))
                                           .filter(wNum => !isNaN(wNum) && wNum > 0);

                    if (widthsInGroup.length === 0) {
                        // Only log info if some width string was entered but resulted in no valid widths.
                        // If widthString was empty, it's fine to skip silently if no parts either.
                        if (group.dimensions.widthString.trim() !== "") {
                           processingInfoMessages.push(`Info: ${groupLabel} has no valid positive widths specified and was skipped.`);
                        }
                        return; // Skip group if no valid widths
                    }

                    totalKebenetsProcessed += widthsInGroup.length; // Count each width instance as one KEBENET

                    widthsInGroup.forEach(currentWidth => {
                        let KEBENETItemProcessingErrorEncountered = false; // Flag for errors within a single KEBENET item
                        const scope = {
                            // KEBENET dimensions
                            width: currentWidth,
                            height: parseFloat(group.dimensions.height),
                            depth: parseFloat(group.dimensions.depth),
                            // Global config
                            Sheet_Thickness: parseFloat(this.globalConfig.sheetThickness), // Alias for formulas
                            thick: parseFloat(this.globalConfig.sheetThickness),          // Common shorthand
                            thick2: parseFloat(this.globalConfig.sheetThickness) * 2,     // Common calculation
                            // Door specific
                            doorgap: parseFloat(this.globalConfig.doorGap),
                            door_gap_top: parseFloat(this.globalConfig.doorGapTop),
                            count: parseInt(group.groupSpecificConfig.doorCount) || 0, // Number of doors for this KEBENET type
                            // Derived common value (example: width per door minus a gap)
                            doorcut: ((currentWidth / Math.max(1, parseInt(group.groupSpecificConfig.doorCount) || 1)) - parseFloat(this.globalConfig.doorGap))
                        };

                        group.partDefinitions.forEach(pd => {
                            if (KEBENETItemProcessingErrorEncountered) return; // Skip further parts for this KEBENET if an error occurred

                            const partLabel = `Part "${pd.name || 'Unnamed'}" in ${groupLabel} (Width: ${currentWidth})`;

                            let calculatedWidth = this.evalFormula(pd.widthStr, scope);
                            let calculatedHeight = this.evalFormula(pd.heightStr, scope);
                            let calculatedQuantity = this.evalFormula(pd.quantityStr, scope);

                            // Check for formula evaluation errors
                            if (calculatedWidth === 'Error' || calculatedHeight === 'Error' || calculatedQuantity === 'Error') {
                                processingErrorMessages.push(`Calculation Error: ${partLabel}. Check formula and console for details.`);
                                KEBENETItemProcessingErrorEncountered = true; return;
                            }
                            // Ensure quantity is a non-negative integer
                            calculatedQuantity = (typeof calculatedQuantity === 'number') ? Math.max(0, Math.floor(calculatedQuantity)) : 0;

                            // Validate calculated dimensions (must be positive if quantity > 0)
                            if (calculatedQuantity > 0) {
                                if (typeof calculatedWidth !== 'number' || calculatedWidth <= 0) {
                                    processingErrorMessages.push(`Invalid Width: ${partLabel} calculated to ${calculatedWidth}. Must be positive.`);
                                    KEBENETItemProcessingErrorEncountered = true;
                                }
                                if (typeof calculatedHeight !== 'number' || calculatedHeight <= 0) {
                                    processingErrorMessages.push(`Invalid Height: ${partLabel} calculated to ${calculatedHeight}. Must be positive.`);
                                    KEBENETItemProcessingErrorEncountered = true;
                                }
                            }
                            if (KEBENETItemProcessingErrorEncountered) return;


                            if (calculatedQuantity > 0) { // Only add parts with a quantity greater than 0
                                allCalculatedParts.push({
                                    name: pd.name,
                                    calculatedWidth: calculatedWidth,
                                    calculatedHeight: calculatedHeight,
                                    calculatedQuantity: calculatedQuantity
                                });
                            }
                        });
                    });
                });
                return { allCalculatedParts, totalKebenetsProcessed, processingInfoMessages, processingErrorMessages };
            },
            _aggregateAndSortParts(allCalculatedParts) {
                // Calls the globally defined helper function
                return aggregateAndSortPartsLogic(allCalculatedParts);
            },

            // --- Gemini API Methods ---
            async callGeminiAPI(promptText, loadingFlagSetter, resultSetter) {
                if (!this.apiKey || this.apiKey.trim() === "") {
                    this.geminiError = "API Key for Gemini is not set. Please provide it in the script.";
                    console.error("Gemini API Key is missing.");
                    loadingFlagSetter(false);
                    resultSetter(''); // Clear any previous results
                    return;
                }

                loadingFlagSetter(true); this.geminiError = ''; // Reset error before new call
                const payload = { contents: [{ role: "user", parts: [{ text: promptText }] }] };
                // Ensure you are using the correct model name if it's not gemini-2.0-flash or if it changes.
                const apiUrl = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${this.apiKey}`;

                try {
                    const response = await fetch(apiUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({ error: { message: "Unknown API error structure" } }));
                        throw new Error(`API Error (${response.status}): ${errorData.error?.message || response.statusText}`);
                    }
                    const result = await response.json();
                    if (result.candidates && result.candidates.length > 0 &&
                        result.candidates[0].content && result.candidates[0].content.parts &&
                        result.candidates[0].content.parts.length > 0 && result.candidates[0].content.parts[0].text) {
                        resultSetter(result.candidates[0].content.parts[0].text.trim());
                    } else {
                        // Log the actual response if the structure is unexpected
                        console.warn("Unexpected API response structure:", result);
                        if (result.promptFeedback && result.promptFeedback.blockReason) {
                             throw new Error(`Content blocked by API. Reason: ${result.promptFeedback.blockReason}. Details: ${result.promptFeedback.blockReasonMessage || 'No additional details.'}`);
                        }
                        throw new Error('No valid content received from AI. The response might be empty or improperly formatted.');
                    }
                } catch (e) {
                    console.error('Gemini API Call Error:', e);
                    this.geminiError = `AI Assistant Error: ${e.message}. Check console for more details.`;
                    resultSetter(''); // Clear any partial or old results on error
                }
                finally {
                    loadingFlagSetter(false);
                }
            },
            getOptimizationTips() {
                if (this.resultRows.length === 0) { this.geminiError = "Please generate a cutlist first to get optimization tips."; return; }
                const partsListString = this.resultRows.map(r => `- ${r.name}: ${r.calculatedWidth} x ${r.calculatedHeight} (Quantity: ${r.calculatedQuantity})`).join("\n");
                const prompt = `I have a woodworking project (KEBENET Dapo - cabinets) and the following aggregated cutlist of parts:\n${partsListString}\n\nMy standard sheet material dimensions are: Width ${this.globalConfig.sheetWidth}, Height ${this.globalConfig.sheetHeight}, Thickness ${this.globalConfig.sheetThickness}.\n\nPlease provide concise, actionable tips for arranging these parts on the sheets to minimize waste. Focus on general strategies and common sense approaches for manual layout. Keep the advice practical for a woodworker.`;
                this.callGeminiAPI(prompt.trim(), v => this.isLoadingOptimizationTips = v, r => this.optimizationTips = r);
            },
            getProjectSummary() {
                if (this.resultRows.length === 0 && this.kebenetGroups.length === 0) { this.geminiError = "Please define KEBENET groups and generate a cutlist to get a project summary."; return; }

                let projectDesc = `This KEBENET Dapo (cabinet) project consists of ${this.kebenetGroups.length} KEBENET group(s).\nDetails for each group:\n`;
                this.kebenetGroups.forEach(g => {
                    const widths = g.dimensions.widthString.trim().split(/\s+/).filter(w => w).join(", ") || 'N/A';
                    projectDesc += `- Group "${g.groupName}": KEBENETs with overall dimensions (Widths: [${widths}] x Height: ${g.dimensions.height} x Depth: ${g.dimensions.depth}). Each KEBENET in this group has ${g.groupSpecificConfig.doorCount} door(s).\n`;
                    if (g.sourceTemplateName && g.sourceTemplateName !== "Custom") {
                        projectDesc += `  (Based on template: "${g.sourceTemplateName}")\n`;
                    }
                });

                if (this.resultRows.length > 0) {
                    projectDesc += `\nThe final aggregated cutlist includes ${this.resultRows.length} unique part types (name/dimension combinations):\n`;
                    const partsSummary = this.resultRows.map(r => `  - ${r.calculatedQuantity}x ${r.name} (each ${r.calculatedWidth} x ${r.calculatedHeight})`).join("\n");
                    projectDesc += partsSummary;
                } else {
                    projectDesc += "\nNo parts were generated in the final cutlist. This could be due to calculation errors, all part quantities evaluating to zero, or no valid KEBENETs being processed (e.g., groups lacking valid widths or part definitions).";
                }
                projectDesc += `\nGlobal configuration used: Sheet Thickness ${this.globalConfig.sheetThickness}, Standard Sheet Size ${this.globalConfig.sheetWidth}x${this.globalConfig.sheetHeight}, Door Gaps (Side/Between: ${this.globalConfig.doorGap}, Top/Overall: ${this.globalConfig.doorGapTop}).`;

                const prompt = `Please generate a brief, easy-to-read project summary based on the following KEBENET Dapo (cabinet) project information. Highlight the number of groups, types of KEBENETs, and a quick overview of the generated parts if available. Be concise and informative.\n\nProject Information:\n${projectDesc}`;
                this.callGeminiAPI(prompt.trim(), v => this.isLoadingProjectSummary = v, r => this.projectSummary = r);
            }
        }
    }
    </script>
</body>
</html>
