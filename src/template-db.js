// src/template-database.js

/**
 * This script provides functions specific to managing Part Templates in the database.
 * It relies on the generic CRUD functions (getAllItems, createItem, updateItem, deleteItem)
 * being available globally from crud.js.
 */

const TEMPLATE_STORE_ROLE = 'user'; // Or 'admin', or make this configurable
const TEMPLATE_STORE_NAME = 'part_templates';

/**
 * Loads all part templates from the database.
 * @returns {Promise<Array<Object>>} A promise that resolves to an array of template objects.
 * @throws {Error} If the database operation fails.
 */
async function loadTemplatesFromDBService() {
    try {
        const templates = await getAllItems(TEMPLATE_STORE_ROLE, TEMPLATE_STORE_NAME);
        if (templates && Array.isArray(templates)) {
            // Ensure essential structure, e.g., partDefinitions is an array
            return templates.map(t => ({
                ...t,
                id: t.id, // Ensure DB ID is the primary ID
                partDefinitions: Array.isArray(t.partDefinitions) ? t.partDefinitions : []
            }));
        }
        console.warn("loadTemplatesFromDBService received no templates or unexpected format.");
        return []; // Return empty array if no templates or unexpected format
    } catch (error) {
        console.error('Error in loadTemplatesFromDBService:', error);
        throw new Error(`Failed to load templates: ${error.message}`);
    }
}

/**
 * Adds a new part template to the database.
 * @param {Object} templateData - The template object to create. Should not include an 'id' if the DB assigns it.
 * @returns {Promise<Object>} A promise that resolves to the created template object (ideally with DB-assigned ID).
 * @throws {Error} If the database operation fails or templateData is invalid.
 */
async function addTemplateToDBService(templateData) {
    if (!templateData || typeof templateData !== 'object' || !templateData.name) {
        throw new Error("Invalid template data provided for adding.");
    }
    // Ensure no client-side 'id' is sent if the DB is expected to generate it.
    // crud.js's createItem might handle this, or your backend API.
    // For safety, let's assume templateData comes without an 'id' or 'id' is ignored by backend on create.
    const { id, ...dataToSend } = templateData;


    try {
        const createdTemplate = await createItem(TEMPLATE_STORE_ROLE, TEMPLATE_STORE_NAME, dataToSend);
        if (!createdTemplate || !createdTemplate.id) {
            console.warn("addTemplateToDBService: createItem did not return a template with an ID.", createdTemplate);
            // Depending on API, you might throw an error or return what was received.
            // Throwing an error if ID is crucial for frontend state.
            throw new Error("Template created, but no ID was returned from the server.");
        }
        return createdTemplate;
    } catch (error) {
        console.error('Error in addTemplateToDBService:', error);
        throw new Error(`Failed to add template: ${error.message}`);
    }
}

/**
 * Updates an existing part template in the database.
 * @param {string|number} templateId - The ID of the template to update.
 * @param {Object} templateData - The full template object with updated data.
 * @returns {Promise<Object>} A promise that resolves to the updated template object.
 * @throws {Error} If the database operation fails or parameters are invalid.
 */
async function updateTemplateInDBService(templateId, templateData) {
    if (!templateId) {
        throw new Error("Template ID is required for updating.");
    }
    if (!templateData || typeof templateData !== 'object') {
        throw new Error("Invalid template data provided for updating.");
    }
    try {
        // Ensure the ID in the payload matches the ID in the URL, or that the backend uses the URL ID.
        const payload = { ...templateData, id: templateId };
        const updatedTemplate = await updateItem(TEMPLATE_STORE_ROLE, TEMPLATE_STORE_NAME, templateId, payload);
        return updatedTemplate; // Assuming updateItem returns the updated object or a success indicator
    } catch (error) {
        console.error(`Error in updateTemplateInDBService for ID ${templateId}:`, error);
        throw new Error(`Failed to update template: ${error.message}`);
    }
}

/**
 * Deletes a part template from the database.
 * @param {string|number} templateId - The ID of the template to delete.
 * @returns {Promise<Object>} A promise that resolves to the result of the delete operation.
 * @throws {Error} If the database operation fails or templateId is invalid.
 */
async function deleteTemplateFromDBService(templateId) {
    if (!templateId) {
        throw new Error("Template ID is required for deleting.");
    }
    try {
        const result = await deleteItem(TEMPLATE_STORE_ROLE, TEMPLATE_STORE_NAME, templateId);
        return result; // deleteItem might return a confirmation or be void on success
    } catch (error) {
        console.error(`Error in deleteTemplateFromDBService for ID ${templateId}:`, error);
        throw new Error(`Failed to delete template: ${error.message}`);
    }
}

// You can choose to expose these functions globally (if not using modules)
// or group them in an object if you prefer namespacing.
// For simplicity with current setup, they'll be global when this script is included.
// Example of namespacing (optional, requires calling e.g., TemplateDB.load()):

const TemplateDB = {
    load: loadTemplatesFromDBService,
    add: addTemplateToDBService,
    update: updateTemplateInDBService,
    delete: deleteTemplateFromDBService
};
