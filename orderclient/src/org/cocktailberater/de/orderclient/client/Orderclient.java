package org.cocktailberater.de.orderclient.client;

import java.util.HashMap;

import com.google.gwt.core.client.EntryPoint;
import com.google.gwt.core.client.GWT;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.event.dom.client.ClickHandler;
import com.google.gwt.event.dom.client.FocusEvent;
import com.google.gwt.event.dom.client.FocusHandler;
import com.google.gwt.user.client.rpc.AsyncCallback;
import com.google.gwt.user.client.ui.Button;
import com.google.gwt.user.client.ui.DialogBox;
import com.google.gwt.user.client.ui.DockPanel;
import com.google.gwt.user.client.ui.Label;
import com.google.gwt.user.client.ui.ListBox;
import com.google.gwt.user.client.ui.PasswordTextBox;
import com.google.gwt.user.client.ui.RootPanel;
import com.google.gwt.user.client.ui.TextBox;

/**
 * Entry point classes define <code>onModuleLoad()</code>.
 */
public class Orderclient implements EntryPoint {

	private static final String STARS = "**********";

	private static final String YOU_EXAMPLE_COM = "you@example.com";

	/**
	 * Create a remote service proxy to talk to the server-side get bars
	 * service.
	 */
	private final BarServiceAsync getBarsService = (BarServiceAsync) GWT
			.create(BarService.class);

	/**
	 * select box with available bars
	 */
	private final ListBox barBox = new ListBox();

	/**
	 * select box with available recipes
	 */
	private final ListBox recipeBox = new ListBox();

	/**
	 * builds the user interface
	 */
	public void onModuleLoad() {
		drawLoginUI();
	}

	private void drawLoginUI() {
		// fill select box with bars
		barBox.addItem("-select a bar-");
		getBars();
		// other user input fields
		final TextBox emailField = new TextBox();
		emailField.setText(YOU_EXAMPLE_COM);
		// on focus delete default input
		emailField.addFocusHandler(new FocusHandler() {
			@Override
			public void onFocus(FocusEvent event) {
				if (emailField.getText().equals(YOU_EXAMPLE_COM)) {
					emailField.setText("");
				}
			}
		});
		final PasswordTextBox passwordField = new PasswordTextBox();
		passwordField.setText(STARS);
		// on focus delete default input
		passwordField.addFocusHandler(new FocusHandler() {
			@Override
			public void onFocus(FocusEvent event) {
				if (passwordField.getText().equals(STARS)) {
					passwordField.setText("");
				}
			}
		});
		final Button enterButton = new Button("Enter");
		enterButton.addStyleName("enterButton");
		RootPanel.get("barFieldContainer").add(barBox);
		RootPanel.get("emailFieldContainer").add(emailField);
		RootPanel.get("passwordFieldContainer").add(passwordField);
		RootPanel.get("enterButtonContainer").add(enterButton);
		barBox.setFocus(true);

		enterButton.addClickHandler(new ClickHandler() {

			@Override
			public void onClick(ClickEvent event) {
				final DialogBox loginBox = new DialogBox(false);
				// try to authenticate
				getBarsService.authenticate(emailField.getValue(),
						passwordField.getValue(), new AsyncCallback<Boolean>() {

							@Override
							public void onSuccess(Boolean authenticated) {
								// show success
								Button okButton = new Button("Thanks!");
								okButton.addClickHandler(new ClickHandler() {

									@Override
									public void onClick(ClickEvent event) {
										loginBox.hide();
										RootPanel.get("container").setVisible(
												false);
										RootPanel.get("container2").setVisible(
												true);
									}
								});
								
								Button againButton = new Button("Try Again!");
								againButton.addClickHandler(new ClickHandler() {

									@Override
									public void onClick(ClickEvent event) {
										loginBox.hide();

									}
								});
								loginBox.setText("Login");
								DockPanel content = new DockPanel();
								loginBox.add(content);
								Label loading = new Label();
								if (authenticated) {
									loading
											.setText("You are logged in the bar "
													+ barBox
															.getValue(barBox
																	.getSelectedIndex())
													+ " as "
													+ emailField.getValue());
									content.add(okButton, DockPanel.SOUTH);

								} else {
									loading
											.setText("You are *NOT* logged in the bar "
													+ barBox
															.getValue(barBox
																	.getSelectedIndex())
													+ " as "
													+ emailField.getValue());
									content.add(againButton, DockPanel.SOUTH);
								}
								content.add(loading, DockPanel.NORTH);
								
								loginBox.center();
								loginBox.show();
							}

							@Override
							public void onFailure(Throwable caught) {
								// TODO Auto-generated method stub

							}
						});

			}
		});

		final Button orderButton = new Button("Order");
		orderButton.addStyleName("orderButton");
		RootPanel.get("cocktailFieldContainer").add(recipeBox);
		RootPanel.get("orderButtonContainer").add(orderButton);
		orderButton.setFocus(true);
		// fill select box with bars
		recipeBox.addItem("-select a cocktail-");
		getRecipes();
	}

	/**
	 * fills the bar select box with available bars
	 */
	public void getBars() {
		getBarsService.getBars(new AsyncCallback<HashMap<Integer, String>>() {

			public void onSuccess(HashMap<Integer, String> result) {
				for (Integer bar : result.keySet()) {
					barBox.addItem(result.get(bar),Integer.toString(bar));
				}
			}

			public void onFailure(Throwable caught) {
				barBox.addItem("-no bars available-");
			}
		});
	}

	/**
	 * fills the recipe select box with available recipes
	 */
	public void getRecipes() {
		getBarsService.getRecipes(new AsyncCallback<HashMap<Integer, String>>() {

			public void onSuccess(HashMap<Integer, String> result) {
				for (Integer recipe : result.keySet()) {
					recipeBox.addItem(result.get(recipe),Integer.toString(recipe));
				}
			}

			public void onFailure(Throwable caught) {
				recipeBox.addItem("-no recipes available-");
			}
		});
	}
}
