package org.cocktailberater.de.kitchenclient;

import java.util.HashMap;

import org.cocktailberater.de.orderclient.BarService;
import org.cocktailberater.de.orderclient.BarServiceAsync;

import com.google.gwt.core.client.EntryPoint;
import com.google.gwt.core.client.GWT;
import com.google.gwt.event.dom.client.ChangeEvent;
import com.google.gwt.event.dom.client.ChangeHandler;
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
public class Kitchenclient implements EntryPoint {

	/**
	 * default password
	 */
	private static final String STARS = "**********";

	/**
	 * default email address
	 */
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
	 * select box with available partys
	 */
	private final ListBox partyBox = new ListBox();

	/**
	 * select box with available recipes
	 */
	private final ListBox recipeBox = new ListBox();

	/**
	 * password field
	 */
	final PasswordTextBox passwordField = new PasswordTextBox();

	/**
	 * email field
	 */
	final TextBox emailField = new TextBox();

	/**
	 * ordered dialog Box
	 */
	final DialogBox orderedBox = new DialogBox(false);

	/**
	 * order failed dialog box
	 */
	final DialogBox orderFailedBox = new DialogBox(false);

	/**
	 * logged in dialog box
	 */
	final DialogBox loggedInBox = new DialogBox(false);

	/**
	 * login failed dialog box
	 */
	final DialogBox logginFailedBox = new DialogBox(false);

	/**
	 * builds the user interface
	 */
	public void onModuleLoad() {
		drawLoginUI();
		drawOrderUI();
	}

	/**
	 * draws the login ui with the login form and the login successful and login
	 * failed dialog box
	 */
	private void drawLoginUI() {
		// fill select box with bars
		barBox.addItem("-select a bar-");
		getBars();
		barBox.addChangeHandler(getPartys());
		// fill select box with party
		partyBox.addItem("-select a party-");
		// set other user input fields
		emailField.setText(YOU_EXAMPLE_COM);
		passwordField.setText(STARS);
		// on focus delete default input
		emailField.addFocusHandler(emptyEmailBox());
		passwordField.addFocusHandler(emptyPasswordBox());
		// set up enter button
		final Button enterButton = new Button("Enter");
		enterButton.addStyleName("enterButton");
		enterButton.addClickHandler(login(emailField, passwordField));
		// place user input fields
		RootPanel.get("barFieldContainer").add(barBox);
		RootPanel.get("partyFieldContainer").add(partyBox);
		RootPanel.get("emailFieldContainer").add(emailField);
		RootPanel.get("passwordFieldContainer").add(passwordField);
		RootPanel.get("enterButtonContainer").add(enterButton);
		barBox.setFocus(true);

		// draw logged in box
		Button okButton = new Button("Thanks!");
		okButton.addClickHandler(new ClickHandler() {
			@Override
			public void onClick(ClickEvent event) {
				loggedInBox.hide();
				RootPanel.get("container").setVisible(false);
				RootPanel.get("container2").setVisible(true);
			}
		});
		loggedInBox.setText("Logged In!");
		DockPanel content = new DockPanel();
		loggedInBox.add(content);
		Label loading = new Label();
		loading.setText("You are logged in!");
		content.add(okButton, DockPanel.SOUTH);
		content.add(loading, DockPanel.NORTH);

		// draw login failed box
		Button againButton = new Button("Try Again!");
		againButton.addClickHandler(new ClickHandler() {
			@Override
			public void onClick(ClickEvent event) {
				logginFailedBox.hide();
			}
		});
		logginFailedBox.setText("Login Failed!");
		DockPanel content2 = new DockPanel();
		logginFailedBox.add(content2);
		Label loading2 = new Label();
		loading2.setText("You are *NOT* logged in. Try Again!");
		content2.add(againButton, DockPanel.SOUTH);
		content2.add(loading2, DockPanel.NORTH);

	}

	/**
	 * draws the order ui with the order form and the order successful and order
	 * failed dialog box
	 */
	private void drawOrderUI() {
		// fill select box with bars
		recipeBox.addItem("-select a cocktail-");
		getRecipes();
		// set up order button
		final Button orderButton = new Button("Order");
		orderButton.addStyleName("orderButton");
		orderButton.setFocus(true);
		orderButton.addClickHandler(order());
		// place use input fields
		RootPanel.get("cocktailFieldContainer").add(recipeBox);
		RootPanel.get("orderButtonContainer").add(orderButton);

		// draw order successful box
		DockPanel content = new DockPanel();
		orderedBox.add(content);
		Label loading = new Label();
		// show success
		Button okButton = new Button("Thanks!");
		orderedBox.setTitle("Success");
		loading.setText("Your order has been accepted!");
		okButton.addClickHandler(new ClickHandler() {
			@Override
			public void onClick(ClickEvent event) {
				emailField.setText(YOU_EXAMPLE_COM);
				passwordField.setText(STARS);
				recipeBox.setSelectedIndex(0);
				orderedBox.hide();
				RootPanel.get("container2").setVisible(false);
				RootPanel.get("container").setVisible(true);
			}
		});
		content.add(loading, DockPanel.NORTH);
		content.add(okButton, DockPanel.SOUTH);

		// draw order failed box
		DockPanel content2 = new DockPanel();
		Button againButton = new Button("Try Again!");
		againButton.addClickHandler(new ClickHandler() {
			@Override
			public void onClick(ClickEvent event) {
				orderFailedBox.hide();
			}
		});
		orderFailedBox.setTitle("Error");
		Label loading2 = new Label();
		loading2.setText("Your order has *NOT* been accepted!");
		content2.add(loading2, DockPanel.NORTH);
		content2.add(againButton, DockPanel.SOUTH);
	}

	/**
	 * tries to order a recipe at a given bar and shows the success
	 * 
	 * @return
	 */
	private ClickHandler order() {
		return new ClickHandler() {
			@Override
			public void onClick(ClickEvent event) {
				Integer recipe = Integer.parseInt(recipeBox.getValue(recipeBox
						.getSelectedIndex()));
				Integer bar = Integer.parseInt(recipeBox.getValue(barBox
						.getSelectedIndex()));
				getBarsService.orderRecipe(recipe, bar,
						new AsyncCallback<Boolean>() {
							@Override
							public void onFailure(Throwable caught) {
								System.out.println("*NOT* cool");
							}

							@Override
							public void onSuccess(Boolean success) {
								if (success) {
									orderedBox.center();
									orderedBox.show();
								} else {
									orderFailedBox.center();
									orderFailedBox.show();
								}
							}
						});
			}
		};
	}

	/**
	 * tries to login a member with the given email and password and shows the
	 * success
	 * 
	 * @param emailField
	 * @param passwordField
	 * @return
	 */
	private ClickHandler login(final TextBox emailField,
			final PasswordTextBox passwordField) {
		return new ClickHandler() {

			@Override
			public void onClick(ClickEvent event) {
				// try to authenticate
				getBarsService.authenticate(emailField.getValue(),
						passwordField.getValue(), new AsyncCallback<Boolean>() {

							@Override
							public void onSuccess(Boolean authenticated) {
								if (authenticated) {
									loggedInBox.center();
									loggedInBox.show();
								} else {
									logginFailedBox.center();
									logginFailedBox.show();
								}
							}

							@Override
							public void onFailure(Throwable caught) {
								System.out.println("*NOT* cool");
							}
						});

			}
		};
	}

	/**
	 * resets the password input field
	 * 
	 * @return
	 */
	private FocusHandler emptyPasswordBox() {
		return new FocusHandler() {
			@Override
			public void onFocus(FocusEvent event) {
				if (passwordField.getText().equals(STARS)) {
					passwordField.setText("");
				}
			}
		};
	}

	/**
	 * resets the email input field
	 * 
	 * @return
	 */
	private FocusHandler emptyEmailBox() {
		return new FocusHandler() {
			@Override
			public void onFocus(FocusEvent event) {
				if (emailField.getText().equals(YOU_EXAMPLE_COM)) {
					emailField.setText("");
				}
			}
		};
	}

	/**
	 * fills the party select box with available partys of a given bar
	 * 
	 * @return
	 */
	private ChangeHandler getPartys() {
		return new ChangeHandler() {
			@Override
			public void onChange(ChangeEvent event) {
				getBarsService.getPartys(Integer.parseInt(barBox
						.getValue(barBox.getSelectedIndex())),
						new AsyncCallback<HashMap<Integer, String>>() {
							@Override
							public void onFailure(Throwable caught) {
								System.out.println("*NOT* cool");
							}

							@Override
							public void onSuccess(
									HashMap<Integer, String> result) {
								for (Integer party : result.keySet()) {
									partyBox.addItem(result.get(party), Integer
											.toString(party));
								}
							}
						});
			}
		};
	}

	/**
	 * fills the bar select box with available bars
	 */
	public void getBars() {
		getBarsService.getBars(new AsyncCallback<HashMap<Integer, String>>() {

			public void onSuccess(HashMap<Integer, String> result) {
				for (Integer bar : result.keySet()) {
					barBox.addItem(result.get(bar), Integer.toString(bar));
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
		getBarsService
				.getRecipes(new AsyncCallback<HashMap<Integer, String>>() {
					public void onSuccess(HashMap<Integer, String> result) {
						for (Integer recipe : result.keySet()) {
							recipeBox.addItem(result.get(recipe), Integer
									.toString(recipe));
						}
					}

					public void onFailure(Throwable caught) {
						recipeBox.addItem("-no recipes available-");
					}
				});
	}
}
