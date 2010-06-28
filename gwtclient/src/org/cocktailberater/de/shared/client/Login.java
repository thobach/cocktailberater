package org.cocktailberater.de.shared.client;

import com.google.gwt.core.client.GWT;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.event.dom.client.ClickHandler;
import com.google.gwt.event.dom.client.FocusEvent;
import com.google.gwt.event.dom.client.KeyPressEvent;
import com.google.gwt.event.dom.client.KeyPressHandler;
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
	 * login failed dialog box
	 */
	final DialogBox logginFailedBox = new DialogBox(false);
	
	/**
	 * registration failed dialog box
	 */
	final DialogBox registrationFailedBox = new DialogBox(false);

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
	Label emailLabel;
	@UiField
	Label pwdLabel;
	@UiField
	TextBox email;
	@UiField
	PasswordTextBox password;

	@UiField
	Button register;
	@UiField
	Label emailRegisterLabel;
	@UiField
	Label pwd1Label;
	@UiField
	Label pwd2Label;
	@UiField
	TextBox emailRegister;
	@UiField
	PasswordTextBox password1;
	@UiField
	PasswordTextBox password2;
	
	private Login instance;

	protected Widget nextWidget;

	public void setNextWidget(Widget nextWidget) {
		this.nextWidget = nextWidget;
	}

	public Login() {

		instance = this;

		initWidget(uiBinder.createAndBindUi(this));
		setupLogin();
		setupRegister();

	}

	private void setupLogin() {
		login.setText("Login");
		login.setStyleName(Resources.INSTANCE.style().button());

		email.setText(YOU_EXAMPLE_COM);
		password.setText(STARS);
		password.addKeyPressHandler(new KeyPressHandler() {
			@Override
			public void onKeyPress(KeyPressEvent event) {
				// check if enter key was pressed
				if (event.getCharCode() == 13) {
					loginOnClick(null);
				}
			}
		});

		emailLabel.addStyleName(Resources.INSTANCE.style().label());
		pwdLabel.addStyleName(Resources.INSTANCE.style().label());

		// draw login failed box
		Button againButton = new Button("Nochmal versuchen!");
		againButton.setStyleName(Resources.INSTANCE.style().longbutton());
		againButton.addClickHandler(new ClickHandler() {
			@Override
			public void onClick(ClickEvent event) {
				logginFailedBox.hide();
			}
		});
		logginFailedBox.setText("Login fehlgeschlagen!");
		DockPanel content2 = new DockPanel();
		logginFailedBox.add(content2);
		Label loading2 = new Label();
		loading2.setText("Du konntest nicht eingeloggt werden.");
		loading2.setStyleName(Resources.INSTANCE.style().large());
		content2.add(againButton, DockPanel.SOUTH);
		content2.add(loading2, DockPanel.NORTH);
	}
	
	private void setupRegister() {
		register.setText("Registrieren");
		register.setStyleName(Resources.INSTANCE.style().button());

		emailRegister.setText(YOU_EXAMPLE_COM);
		password1.addKeyPressHandler(new KeyPressHandler() {
			@Override
			public void onKeyPress(KeyPressEvent event) {
				// check if enter key was pressed
				if (event.getCharCode() == 13) {
					registerOnClick(null);
				}
			}
		});
		password2.addKeyPressHandler(new KeyPressHandler() {
			@Override
			public void onKeyPress(KeyPressEvent event) {
				// check if enter key was pressed
				if (event.getCharCode() == 13) {
					registerOnClick(null);
				}
			}
		});

		emailRegisterLabel.addStyleName(Resources.INSTANCE.style().label());
		pwd1Label.addStyleName(Resources.INSTANCE.style().label());
		pwd2Label.addStyleName(Resources.INSTANCE.style().label());

		// draw registration failed box
		Button againButton = new Button("Nochmal versuchen!");
		againButton.setStyleName(Resources.INSTANCE.style().longbutton());
		againButton.addClickHandler(new ClickHandler() {
			@Override
			public void onClick(ClickEvent event) {
				registrationFailedBox.hide();
			}
		});
		registrationFailedBox.setText("Registrierung fehlgeschlagen!");
		DockPanel content2 = new DockPanel();
		registrationFailedBox.add(content2);
		Label loading2 = new Label();
		loading2.setText("Du konntest nicht registriert werden.");
		loading2.setStyleName(Resources.INSTANCE.style().large());
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
							RootPanel.get().remove(instance);
							if (nextWidget != null) {
								email.setText(YOU_EXAMPLE_COM);
								password.setText(STARS);
								RootPanel.get().add(nextWidget);
							}
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
	
	@UiHandler("register")
	void registerOnClick(ClickEvent e) {
		// try to register
		getBarsService.register(email.getValue(), password.getValue(),
				new AsyncCallback<Boolean>() {

					@Override
					public void onSuccess(Boolean registered) {
						if (registered) {
							RootPanel.get().remove(instance);
							if (nextWidget != null) {
								emailRegister.setText(YOU_EXAMPLE_COM);
								password1.setText(STARS);
								RootPanel.get().add(nextWidget);
							}
						} else {
							registrationFailedBox.center();
							registrationFailedBox.show();
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
	
	// resets the email input field
	@UiHandler("emailRegister")
	void resetEmailRegisterOnFocus(FocusEvent event) {
		if (emailRegister.getText().equals(YOU_EXAMPLE_COM)) {
			emailRegister.setText("");
		}
	}
}
