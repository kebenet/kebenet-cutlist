<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage KEBENET Template</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="crud.js"></script>
    <style>
        body { background-color: #f4f7f6; padding-top: 20px; font-family: 'Inter', sans-serif; }
        .primary-button { background-color: #2e9578; color: white; } /* */
        .primary-button:hover { background-color: #267d65; color: white; } /* */
        .danger-button { background-color: #f14668; color: white; } /* */
        .danger-button:hover { background-color: #ee3058; color: white; } /* */
        .notification { margin-top: 1rem; }
        .section-title {
            border-bottom: 2px solid #2e9578; /* */
            padding-bottom: 0.5rem; /* */
            margin-bottom: 1.5rem; /* */
        }
        .primary-text { color: #2e9578; } /* */
    </style>
</head>
<body>
    <section class="section">
        <div class="container" x-data="templateForm()">
            <h1 class="title section-title primary-text" x-text="mode === 'add' ? 'Create New KEBENET Template' : 'Edit KEBENET Template'"></h1>

            <form @submit.prevent="saveTemplate">
                <input type="hidden" x-model="template.id">

                <div class="field">
                    <label class="label" for="templateName">Template Name</label>
                    <div class="control">
                        <input class="input" type="text" id="templateName" x-model="template.name" required placeholder="e.g., Standard Base KEBENET">
                    </div>
                </div>

                <h3 class="title is-5 mt-5 primary-text">Part Definitions</h3>
                <div id="partDefinitionsContainer" class="mb-3">
                    <template x-for="(partDef, index) in template.partDefinitions" :key="partDef.alpineId || index">
                        <div class="columns is-multiline part-definition-row py-3 mb-2" style="border-bottom: 1px dashed #dbdbdb;">
                            <div class="column is-3">
                                <div class="field">
                                    <label class="label is-small" :for="'partName-' + index">Part Name</label>
                                    <div class="control">
                                        <input class="input is-small" :id="'partName-' + index" type="text" x-model="partDef.name" placeholder="e.g., Side Panel" required>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-3">
                                <div class="field">
                                    <label class="label is-small" :for="'partWidthStr-' + index">Width Formula</label>
                                    <div class="control">
                                        <input class="input is-small" :id="'partWidthStr-' + index" type="text" x-model="partDef.widthStr" placeholder="e.g., depth" required>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-3">
                                <div class="field">
                                    <label class="label is-small" :for="'partHeightStr-' + index">Height Formula</label>
                                    <div class="control">
                                        <input class="input is-small" :id="'partHeightStr-' + index" type="text" x-model="partDef.heightStr" placeholder="e.g., height" required>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-2">
                                <div class="field">
                                    <label class="label is-small" :for="'partQuantityStr-' + index">Qty Formula</label>
                                    <div class="control">
                                        <input class="input is-small" :id="'partQuantityStr-' + index" type="text" x-model="partDef.quantityStr" placeholder="e.g., 2" required>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-1 is-flex is-align-items-flex-end">
                                <button type="button" class="button is-small is-danger is-outlined" @click="removePartDefinition(index)" title="Remove Part">
                                    <span class="icon is-small"><i class="fas fa-times"></i></span>
                                </button>
                            </div>
                        </div>
                    </template>
                     <template x-if="template.partDefinitions.length === 0">
                        <p class="has-text-grey">No part definitions yet. Click "Add Part" to begin.</p>
                    </template>
                </div>
                <button type="button" class="button is-small primary-button is-outlined" @click="addPartDefinition">
                    <span class="icon is-small"><i class="fas fa-plus"></i></span>
                    <span>Add Part Definition</span>
                </button>

                <div x-show="message" class="notification mt-4" :class="isError ? 'is-danger is-light' : 'is-success is-light'">
                    <button class="delete" @click="message = ''"></button>
                    <span x-text="message"></span>
                </div>

                <div class="field is-grouped mt-5">
                    <div class="control">
                        <button type="submit" class="button primary-button" :disabled="isLoading">
                            <span class="icon"><i class="fas fa-save"></i></span>
                            <span x-text="mode === 'add' ? 'Save Template' : 'Save Changes'"></span>
                            <span x-show="isLoading" class="loading-spinner ml-2"></span>
                        </button>
                    </div>
                    <div class="control">
                        <a href="index.php#template" class="button is-light">Cancel</a>
                    </div>
                     <div class="control" x-show="mode === 'edit'">
                         <button type="button" class="button danger-button" @click="confirmDelete" :disabled="isLoading">
                            <span class="icon"><i class="fas fa-trash-alt"></i></span>
                            <span>Delete Template</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script>
        
        const TEMPLATE_API_ROLE = 'user'; // Or 'admin'
        const TEMPLATE_API_STORE_NAME = 'part_templates';

        function templateForm() {
            return {
                mode: 'add', // 'add' or 'edit'
                template: {
                    id: null,
                    name: '',
                    partDefinitions: []
                },
                isLoading: false,
                message: '',
                isError: false,
                _nextPartDefAlpineId: 1,

                init() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const templateId = urlParams.get('id');
                    const cloneFrom = urlParams.get('cloneFrom');

                    if (templateId) {
                        this.mode = 'edit';
                        this.loadTemplate(templateId);
                    } else if (cloneFrom) {
                        this.mode = 'add';
                        this.loadTemplate(cloneFrom).then(() => {
                            this.template.id = null;
                            this.template.name = `${this.template.name} (Clone)`;
                        });
                    } else {
                        this.mode = 'add';
                        
                        this.template.name = 'Wall Unit';
                        const basicTemplateParts = [
                            { name: 'Side', widthStr: 'depth', heightStr: 'height', quantityStr: '2' },
                            { name: 'Top/Bottom', widthStr: 'width - thick2', heightStr: 'depth', quantityStr: '2' },
                            { name: 'Shelf', widthStr: 'width - thick2', heightStr: 'depth - 1', quantityStr: 'Math.max(0, Math.floor(height / 12))' },
                            { name: 'Back Ply', widthStr: 'width', heightStr: 'height', quantityStr: '1' },
                            { name: 'Door', widthStr: '(width / Math.round(width/20)) - doorgap', heightStr: 'height - door_gap_top', quantityStr: 'count' }
                        ];
                        this.template.partDefinitions = basicTemplateParts.map(part => ({
                            ...part,
                            alpineId: this._nextPartDefAlpineId++
                        }));
                        
                    }
                },

                async loadTemplate(id) {
                    this.isLoading = true;
                    this.message = '';
                    try {
                        
                        const data = await getItemById(TEMPLATE_API_ROLE, TEMPLATE_API_STORE_NAME, id); //
                        this.template.id = data.id;
                        this.template.name = data.name;
                        this.template.partDefinitions = (data.partDefinitions || []).map(pd => ({
                            ...pd,
                            alpineId: this._nextPartDefAlpineId++ 
                        }));
                        if(this.template.partDefinitions.length === 0) {
                            this.addPartDefinition();
                        }
                    } catch (error) {
                        console.error("Error loading template:", error);
                        this.message = `Error loading template: ${error.message}`;
                        this.isError = true;
                    } finally {
                        this.isLoading = false;
                    }
                },

                addPartDefinition() {
                    this.template.partDefinitions.push({
                        alpineId: this._nextPartDefAlpineId++,
                        name: '',
                        widthStr: '',
                        heightStr: '',
                        quantityStr: '1'
                    });
                },

                removePartDefinition(index) {
                    this.template.partDefinitions.splice(index, 1);
                },

                async saveTemplate() {
                    this.isLoading = true;
                    this.message = '';
                    this.isError = false;

                    if (!this.template.name.trim()) {
                        this.message = "Template name cannot be empty.";
                        this.isError = true;
                        this.isLoading = false;
                        return;
                    }
                    for (const pd of this.template.partDefinitions) {
                        if (!pd.name.trim() || !pd.widthStr.trim() || !pd.heightStr.trim() || !pd.quantityStr.trim()) {
                            this.message = "All fields in part definitions are required.";
                            this.isError = true;
                            this.isLoading = false;
                            return;
                        }
                    }

                    const dataToSave = {
                        name: this.template.name,
                        partDefinitions: this.template.partDefinitions.map(pd => ({
                            name: pd.name,
                            widthStr: pd.widthStr,
                            heightStr: pd.heightStr,
                            quantityStr: pd.quantityStr,
                        }))
                    };
                    if (this.mode === 'edit' && this.template.id) {
                        dataToSave.id = this.template.id;
                    }


                    try {
                        if (this.mode === 'edit') {
                            await updateItem(TEMPLATE_API_ROLE, TEMPLATE_API_STORE_NAME, this.template.id, dataToSave);
                            this.message = 'Template updated successfully! Redirecting...';
                        } else {
                            
                            const { id, ...createData } = dataToSave;
                            await createItem(TEMPLATE_API_ROLE, TEMPLATE_API_STORE_NAME, createData);
                            this.message = 'Template created successfully! Redirecting...';
                        }
                        this.isError = false;
                        setTimeout(() => {
                            window.location.href = 'Cutlist-Generator-V2.html#templateTab';
                        }, 1500);
                    } catch (error) {
                        console.error("Error saving template:", error);
                        this.message = `Error saving template: ${error.message}`;
                        this.isError = true;
                    } finally {
                        this.isLoading = false;
                    }
                },

                async confirmDelete() {
                    if (this.mode === 'edit' && this.template.id) {
                        if (confirm(`Are you sure you want to delete the template "${this.template.name}"? This cannot be undone.`)) {
                            this.isLoading = true;
                            this.message = '';
                            this.isError = false;
                            try {
                                await deleteItem(TEMPLATE_API_ROLE, TEMPLATE_API_STORE_NAME, this.template.id); //
                                this.message = 'Template deleted successfully! Redirecting...';
                                this.isError = false;
                                setTimeout(() => {
                                    window.location.href = 'Cutlist-Generator-V2.html#templateTab';
                                }, 1500);
                            } catch (error) {
                                console.error("Error deleting template:", error);
                                this.message = `Error deleting template: ${error.message}`;
                                this.isError = true;
                            } finally {
                                this.isLoading = false;
                            }
                        }
                    }
                }
            };
        }
    </script>
</body>
</html>
