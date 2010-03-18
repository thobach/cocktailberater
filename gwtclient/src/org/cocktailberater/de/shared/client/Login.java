package org.cocktailberater.de.shared.client;

import com.google.gwt.core.client.GWT;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.event.dom.client.ClickHandler;
import com.google.gwt.event.dom.client.FocusEvent;
import com.google.gwt.uibinder.client.UiBinder;
import com.google.gwt.uibinder.client.UiField;
import com.google.gwt.uibinder.client.UiHandler;
import com.google.gwt.user.client.rpc.AsyncCallback;
import com.google.gwt.user.client.ui.Button;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.DialogBox;
import com.google.gwt.user.client.ui.DockPanel;
import com.google.gwt.user.client.ui.Label;
import com.google.gwt.user.client.ui.PasswordTextBox;
import com.google.gwt.user.client.ui.RootPanel;
import com.google.gwt.user.client.ui.TextBox;
import com.google.gwt.user.client.ui.Widget;

public class Login extends Composite {

	private static LoginUiBinder uiBinder = GWT.create(LoginUiBinder.class);

	/**
	 * Create a remote service proxy to talk to the server-side get bars
	 * service.
	 */
	private final BarServiceAsync getBarsService = (BarServiceAsync) GWT
			.create(BarService.class);

	/**
	 * logged in dialog box
	 */
	final DialogBox loggedInBox = new DialogBox(false);

	/**
	 * login failed dialog box
	 */
	final DialogBox logginFailedBox = new DialogBox(false);

	/**
	 * default password
	 */
	private static final String STARS = "**********";

	/**
	 * default email address
	 */
	private static final String YOU_EXAMPLE_COM = "you@example.com";

	interface LoginUiBinder extends UiBinder<Widget, Login> {
	}

	@UiField
	Button login;
	@UiField
	TextBox email;
	@UiField
	PasswordTextBox password;

	private Login instance;

	protected Widget nextWidget;

	public void setNextWidget(Widget nextWidget) {
		this.nextWidget = nextWidget;
	}

	public Login() {

		instance = this;

		initWidget(uiBinder.createAndBindUi(this));
		login.setText("Login");

		email.setText(YOU_EXAMPLE_COM);
		password.setText(STARS);

		// draw logged in box
		Button okButton = new Button("Thanks!");
		okButton.addClickHandler(new ClickHandler() {
			@Override
			public void onClick(ClickEvent event) {
				loggedInBox.hide();
				RootPanel.get().remove(instance);
				if (nextWidget!=null) {
					email.setText(YOU_EXAMPLE_COM);
					password.setText(STARS);
					RootPanel.get().add(nextWidget);
				}
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

	@UiHandler("login")
	void loginOnClick(ClickEvent e) {
		// try to authenticate
		getBarsService.authenticate(email.getValue(), password.getValue(),
				new AsyncCallback<Boolean>() {

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

	// resets the password input field
	@UiHandler("password")
	public void resetPasswordOnFocus(FocusEvent event) {
		if (password.getText().equals(STARS)) {
			password.setText("");
		}
	}

	// resets the email input field
	@UiHandler("email")
	void resetEmailOnFocus(FocusEvent event) {
		if (email.getText().equals(YOU_EXAMPLE_COM)) {
			email.setText("");
		}
	}
}
