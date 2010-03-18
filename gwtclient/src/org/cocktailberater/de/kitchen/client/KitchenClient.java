package org.cocktailberater.de.kitchen.client;

import org.cocktailberater.de.shared.client.BarService;
import org.cocktailberater.de.shared.client.BarServiceAsync;
import org.cocktailberater.de.shared.client.Login;
import org.cocktailberater.de.shared.client.Resources;

import com.google.gwt.core.client.EntryPoint;
import com.google.gwt.core.client.GWT;
import com.google.gwt.user.client.ui.RootPanel;

public class KitchenClient implements EntryPoint {

	/**
	 * Create a remote service proxy to talk to the server-side get bars
	 * service.
	 */
	private final BarServiceAsync getBarsService = (BarServiceAsync) GWT
			.create(BarService.class);

	/**
	 * builds the user interface
	 */
	public void onModuleLoad() {
		GWT.create(Resources.class);
		drawLoginUI();
	}

	/**
	 * draws the login ui with the login form and the login successful and login
	 * failed dialog box
	 */
	private void drawLoginUI() {
		Login login = new Login();
		OrderList orderList = new OrderList();
		orderList.setExitWidget(login);
		login.setNextWidget(orderList);
		RootPanel.get().add(login);
	}

}
