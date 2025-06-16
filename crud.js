// crud.js

const API_BASE_URL = 'https://img.kebenet.com/api/v1';
const MY_KEBENET_API_KEY = '45f70921-aad3-4ded-ba5b-601bcc46ac6e';

/**
 * Helper function to parse error responses.
 * Attempts to parse as JSON, falls back to text if JSON parsing fails.
 * @param {Response} response - The fetch Response object.
 * @returns {Promise<object>} An object containing error details.
 */
async function parseErrorResponse(response) {
    let errorData;
    try {
        errorData = await response.json();
    } catch (e) {
        const errorText = await response.text();
        errorData = {
            error: `Server returned non-JSON error (status ${response.status})`,
            message: errorText || 'No additional error message provided by server.'
        };
    }
    return errorData;
}

async function getAllItems(role, storeName) {
  try {
    const response = await fetch(`${API_BASE_URL}/${role}/${storeName}`, {
      method: 'GET',
      headers: {
        'X-API-Key': MY_KEBENET_API_KEY
      }
    });
    if (!response.ok) {
      const errorData = await parseErrorResponse(response);
      throw new Error(`HTTP error! status: ${response.status}, message: ${errorData.error || errorData.message}`);
    }
    const data = await response.json();
    console.log(`All items from KEBENET Dapo store '${storeName}':`, data);
    return data;
  } catch (error) {
    console.error(`Error fetching all items from KEBENET Dapo store '${storeName}':`, error.message);
    throw error;
  }
}

async function getItemById(role, storeName, itemId) {
  try {
    const response = await fetch(`${API_BASE_URL}/${role}/${storeName}/${itemId}`, {
      method: 'GET',
      headers: {
        'X-API-Key': MY_KEBENET_API_KEY
      }
    });
    if (!response.ok) {
      const errorData = await parseErrorResponse(response);
      throw new Error(`HTTP error! status: ${response.status}, message: ${errorData.error || errorData.message}`);
    }
    const data = await response.json();
    console.log(`Item '${itemId}' from KEBENET Dapo store '${storeName}':`, data);
    return data;
  } catch (error) {
    console.error(`Error fetching item '${itemId}' from KEBENET Dapo store '${storeName}':`, error.message);
    throw error;
  }
}

async function createItem(role, storeName, newItemData) {
  try {
    const response = await fetch(`${API_BASE_URL}/${role}/${storeName}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-API-Key': MY_KEBENET_API_KEY
      },
      body: JSON.stringify(newItemData),
    });

    // Even for errors, try to parse. For success, status 201 is specific.
    const responseData = await parseErrorResponse(response); 

    if (!response.ok || response.status !== 201) { // Check for 201 Created specifically for success
      throw new Error(`HTTP error! status: ${response.status}, message: ${responseData.error || responseData.message}`);
    }

    console.log('KEBENET item created:', responseData);
    return responseData; // This will be the parsed JSON from the server
  } catch (error) {
    console.error(`Error creating KEBENET item in store '${storeName}':`, error.message);
    throw error;
  }
}

async function updateItem(role, storeName, itemId, updatedItemData) {
  try {
    const response = await fetch(`${API_BASE_URL}/${role}/${storeName}/${itemId}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-API-Key': MY_KEBENET_API_KEY
      },
      body: JSON.stringify(updatedItemData),
    });

    const responseData = await parseErrorResponse(response);

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}, message: ${responseData.error || responseData.message}`);
    }

    console.log(`KEBENET item '${itemId}' in store '${storeName}' updated:`, responseData);
    return responseData;
  } catch (error) {
    console.error(`Error updating KEBENET item '${itemId}' in store '${storeName}':`, error.message);
    throw error;
  }
}

async function deleteItem(role, storeName, itemId) {
  try {
    const response = await fetch(`${API_BASE_URL}/${role}/${storeName}/${itemId}`, {
      method: 'DELETE',
      headers: {
        'X-API-Key': MY_KEBENET_API_KEY
      }
    });

    const responseData = await parseErrorResponse(response);

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}, message: ${responseData.error || responseData.message}`);
    }

    console.log(`KEBENET item '${itemId}' in store '${storeName}' deleted:`, responseData);
    return responseData;
  } catch (error) {
    console.error(`Error deleting KEBENET item '${itemId}' in store '${storeName}':`, error.message);
    throw error;
  }
}

async function searchItems(role, storeName, searchField, searchQuery) {
  try {
    const queryParams = new URLSearchParams({
      field: searchField,
      query: searchQuery,
    });
    const response = await fetch(`${API_BASE_URL}/${role}/${storeName}/search?${queryParams}`, {
      method: 'GET',
      headers: {
        'X-API-Key': MY_KEBENET_API_KEY
      }
    });

    if (!response.ok) {
      const errorData = await parseErrorResponse(response);
      throw new Error(`HTTP error! status: ${response.status}, message: ${errorData.error || errorData.message}`);
    }
    const data = await response.json(); // If response.ok, expect valid JSON for search results
    console.log(`Search results in KEBENET Dapo store '${storeName}':`, data);
    return data;
  } catch (error) {
    console.error(`Error searching KEBENET items in store '${storeName}':`, error.message);
    throw error;
  }
}

// Example Usages (kept for reference):
// getAllItems('admin', 'inventory');
// getItemById('user', 'articles', 123);
// createItem('admin', 'orders', { customerName: 'John Doe', product: 'KEBENET Dapo Deluxe', quantity: 1 });
// updateItem('admin', 'products', 456, { price: 199.99, stock: 50 });
// deleteItem('admin', 'archived_users', 789);
// searchItems('user', 'blog_posts', 'title', 'KEBENET');
