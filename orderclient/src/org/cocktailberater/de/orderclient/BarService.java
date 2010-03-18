package org.cocktailberater.de.orderclient;


import java.util.HashMap;

import com.google.gwt.user.client.rpc.RemoteService;
import com.google.gwt.user.client.rpc.RemoteServiceRelativePath;

/**
 * The client side stub for the RPC service.
 */
@RemoteServiceRelativePath("getBars")
public interface BarService extends RemoteService {

	/**
	 * Retrieves all available Bars from the backend.
	 * 
	 * @return list of bars
	 */
	HashMap<Integer, String> getBars();
	
	/**
	 * Retrieves all available partys of a bar from the backend.
	 * @param barId the ID of a bar
	 * @return list of partys at the given bar
	 */
	HashMap<Integer, String> getPartys(Integer barId);
	
	/**
	 * Authenticates a user by email and password.
	 * 
	 * @param email
	 * @param password
	 * @return true if credentials are correct, false otherwise
	 */
	boolean authenticate(String email, String password);

	/**
	 * Returns all available recipes of a bar (from the first available menue).
	 * 
	 * @return
	 */
	HashMap<Integer, String> getRecipes();
	
	/**
	 * Orders a recipe from the menue of a bar for a user.
	 * 
	 * @param recipeId The id of the recipe to be ordered.
	 * @return true if order was accepted, false if not
	 */
	boolean orderRecipe(Integer recipeId, Integer barId);

}
