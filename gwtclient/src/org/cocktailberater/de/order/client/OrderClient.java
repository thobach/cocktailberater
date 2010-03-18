package org.cocktailberater.de.order.client;

import org.cocktailberater.de.shared.client.Login;
import org.cocktailberater.de.shared.client.Resources;

import com.google.gwt.core.client.EntryPoint;
import com.google.gwt.core.client.GWT;
import com.google.gwt.user.client.ui.RootPanel;

public class OrderClient implements EntryPoint {

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
		Order order = new Order();
		
		order.setNextWidget(login);
		login.setNextWidget(order);
		
		RootPanel.get().add(login);
	}

}
