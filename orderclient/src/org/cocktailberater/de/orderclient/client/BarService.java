package org.cocktailberater.de.orderclient.client;


import java.util.HashMap;

import com.google.gwt.user.client.rpc.RemoteService;
import com.google.gwt.user.client.rpc.RemoteServiceRelativePath;

/**
 * The client side stub for the RPC service.
 */
@RemoteServiceRelativePath("getBars")
public interface BarService extends RemoteService {

	/**
	 * Retrieves all available Bars from the backend
	 * 
	 * @return list of bars
	 */
	HashMap<Integer, String> getBars();
	
	boolean authenticate(String email, String password);

	HashMap<Integer, String> getRecipes();

}
