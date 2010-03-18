package org.cocktailberater.de.order.client;

import java.util.HashMap;

import org.cocktailberater.de.shared.client.BarService;
import org.cocktailberater.de.shared.client.BarServiceAsync;
import org.cocktailberater.de.shared.client.Resources;

import com.google.gwt.core.client.GWT;
import com.google.gwt.event.dom.client.ChangeEvent;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.event.dom.client.ClickHandler;
import com.google.gwt.uibinder.client.UiBinder;
import com.google.gwt.uibinder.client.UiField;
import com.google.gwt.uibinder.client.UiHandler;
import com.google.gwt.user.client.rpc.AsyncCallback;
import com.google.gwt.user.client.ui.Button;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.DialogBox;
import com.google.gwt.user.client.ui.DockPanel;
import com.google.gwt.user.client.ui.Label;
import com.google.gwt.user.client.ui.ListBox;
import com.google.gwt.user.client.ui.RootPanel;
import com.google.gwt.user.client.ui.Widget;

public class Order extends Composite {

	private static OrderUiBinder uiBinder = GWT.create(OrderUiBinder.class);

	/**
	 * Create a remote service proxy to talk to the server-side get bars
	 * service.
	 */
	private final BarServiceAsync getBarsService = (BarServiceAsync) GWT
			.create(BarService.class);

	/**
	 * order dialog box
	 */
	final DialogBox orderedBox = new DialogBox(false);

	/**
	 * order failed dialog box
	 */
	final DialogBox orderFailedBox = new DialogBox(false);
	
	protected Widget nextWidget;
	
	public void setNextWidget(Widget nextWidget) {
		this.nextWidget = nextWidget;
	}

	interface OrderUiBinder extends UiBinder<Widget, Order> {
	}

	@UiField
	Button order;
	@UiField
	Button cancel;
	@UiField
	ListBox bar;
	@UiField
	ListBox party;
	@UiField
	ListBox recipe;

	private Order instance;

	public Order() {

		instance = this;
		
		Resources.INSTANCE.style().ensureInjected();

		initWidget(uiBinder.createAndBindUi(this));
		order.setText("Bestellen");
		cancel.setText("Bestellung Abbrechen");
		
		bar.addItem("-Bar auswählen-");
		party.addItem("-Party auswählen-");
		recipe.addItem("-Cocktail auswählen-");

		getBars();

		// draw ordered in box
		Button okButton = new Button("Thanks!");
		okButton.addClickHandler(new ClickHandler() {
			@Override
			public void onClick(ClickEvent event) {
				orderedBox.hide();
				RootPanel.get().remove(instance);
				RootPanel.get().add(nextWidget);
				recipe.setSelectedIndex(0);
			}
		});
		orderedBox.setText("Cocktail wurde bestellt!");
		DockPanel content = new DockPanel();
		orderedBox.add(content);
		Label loading = new Label();
		loading.setText("Ihr Cocktail wurde bestellt!");
		content.add(okButton, DockPanel.SOUTH);
		content.add(loading, DockPanel.NORTH);

		// draw order failed box
		Button againButton = new Button("Nochmal versuchen!");
		againButton.addClickHandler(new ClickHandler() {
			@Override
			public void onClick(ClickEvent event) {
				orderFailedBox.hide();
			}
		});
		orderFailedBox.setText("Bestellung fehlgeschlagen!");
		DockPanel content2 = new DockPanel();
		orderFailedBox.add(content2);
		Label loading2 = new Label();
		loading2
				.setText("Ihre Bestellung ist fehlgeschlagen, versuchen Sie es bitte erneut!");
		content2.add(againButton, DockPanel.SOUTH);
		content2.add(loading2, DockPanel.NORTH);

	}

	@UiHandler("order")
	void orderOnClick(ClickEvent e) {
		Integer recipeVal = Integer.parseInt(recipe.getValue(recipe
				.getSelectedIndex()));
		Integer barVal = Integer.parseInt(recipe.getValue(bar
				.getSelectedIndex()));
		getBarsService.orderRecipe(recipeVal, barVal,
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

	/**
	 * fills the party select box with available partys of a given bar
	 * 
	 * @return
	 */
	@UiHandler("bar")
	void barOnChange(ChangeEvent event) {
		getBarsService.getPartys(Integer.parseInt(bar.getValue(bar
				.getSelectedIndex())),
				new AsyncCallback<HashMap<Integer, String>>() {
					@Override
					public void onFailure(Throwable caught) {
						System.out.println("*NOT* cool");
					}

					@Override
					public void onSuccess(HashMap<Integer, String> result) {
						for (Integer partyVal : result.keySet()) {
							party.addItem(result.get(partyVal), Integer
									.toString(partyVal));
						}
					}
				});
	}

	/**
	 * fills the bar select box with available bars
	 */
	public void getBars() {
		getBarsService.getBars(new AsyncCallback<HashMap<Integer, String>>() {

			public void onSuccess(HashMap<Integer, String> result) {
				for (Integer barVal : result.keySet()) {
					bar.addItem(result.get(barVal), Integer.toString(barVal));
				}
			}

			public void onFailure(Throwable caught) {
				caught.printStackTrace();
				bar.addItem("-no bars available-");
			}
		});
	}

	/**
	 * fills the recipe select box with available recipes
	 */
	@UiHandler("party")
	void getRecipesOnChange(ChangeEvent event) {
		getBarsService
				.getRecipes(new AsyncCallback<HashMap<Integer, String>>() {
					public void onSuccess(HashMap<Integer, String> result) {
						for (Integer recipeVal : result.keySet()) {
							recipe.addItem(result.get(recipeVal), Integer
									.toString(recipeVal));
						}
					}

					public void onFailure(Throwable caught) {
						recipe.addItem("-no recipes available-");
					}
				});
	}
	
	@UiHandler("cancel")
	void logoutOnClick(ClickEvent e){
		RootPanel.get().remove(instance);
		recipe.setSelectedIndex(0);
		RootPanel.get().add(nextWidget);
	}

}
