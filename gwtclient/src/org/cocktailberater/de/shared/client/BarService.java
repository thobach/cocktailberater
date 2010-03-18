package org.cocktailberater.de.shared.client;


import java.util.HashMap;

import com.google.gwt.user.client.rpc.RemoteService;
import com.google.gwt.user.client.rpc.RemoteServiceRelativePath;

/**
 * The client side stub for the RPC service.
 */
@RemoteServiceRelativePath("cocktailberater")
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
	 * Retrieves all open orders of a party from the backend.
	 * @param partyId the ID of a party
	 * @return list of open orders at the given party
	 */
	HashMap<Integer, String> getOrders(Integer partyId);
	
	/**
	 * Markes an order as completed
	 * @param orderId
	 * @return true if order could be completed, false if not
	 */
	boolean completeOrder(Integer orderId);
	
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
	 * Returns the full name of a member.
	 * 
	 * @return first- and lastname of the given member
	 */
	String getMemberName(Integer memberId);
	
	/**
	 * Orders a recipe from the menue of a bar for a user.
	 * 
	 * @param recipeId The id of the recipe to be ordered.
	 * @return true if order was accepted, false if not
	 */
	boolean orderRecipe(Integer recipeId, Integer barId);

}
