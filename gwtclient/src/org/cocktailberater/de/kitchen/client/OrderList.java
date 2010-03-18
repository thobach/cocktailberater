package org.cocktailberater.de.kitchen.client;

import java.util.Collections;
import java.util.HashMap;
import java.util.List;

import org.cocktailberater.de.shared.client.BarService;
import org.cocktailberater.de.shared.client.BarServiceAsync;
import org.cocktailberater.de.shared.client.Resources;

import com.google.gwt.core.client.GWT;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.event.dom.client.ClickHandler;
import com.google.gwt.uibinder.client.UiBinder;
import com.google.gwt.uibinder.client.UiConstructor;
import com.google.gwt.uibinder.client.UiField;
import com.google.gwt.user.client.rpc.AsyncCallback;
import com.google.gwt.user.client.ui.Button;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.HTMLPanel;
import com.google.gwt.user.client.ui.HorizontalPanel;
import com.google.gwt.user.client.ui.Label;
import com.google.gwt.user.client.ui.RootPanel;
import com.google.gwt.user.client.ui.Widget;

public class OrderList extends Composite {

	private static OrderUiBinder uiBinder = GWT.create(OrderUiBinder.class);

	/**
	 * Create a remote service proxy to talk to the server-side get bars
	 * service.
	 */
	private final BarServiceAsync getBarsService = (BarServiceAsync) GWT
			.create(BarService.class);

	protected Widget nextWidget;

	public void setNextWidget(Widget nextWidget) {
		this.nextWidget = nextWidget;
	}

	interface OrderUiBinder extends UiBinder<Widget, OrderList> {
	}

	private OrderList instance;

	@UiField
	HTMLPanel orderList;
	
	@UiField
	HorizontalPanel options;
	
	@UiField
	Button logout;

	@UiField
	Button refresh;	
	
	private Widget exitWidget;

	public OrderList() {
		
		instance = this;

		initWidget(uiBinder.createAndBindUi(this));

		getOrders();
		
		logout.setText("Logout");
		logout.addClickHandler(new ClickHandler() {
			
			@Override
			public void onClick(ClickEvent event) {
				RootPanel.get().remove(instance);
				RootPanel.get().add(exitWidget);
			}
		});
		
		refresh.setText("Aktualisieren");
		refresh.addClickHandler(new ClickHandler() {
			
			@Override
			public void onClick(ClickEvent event) {
				orderList.clear();
				getOrders();
			}
		});
		
		options.setSpacing(8);
		
		
		Resources.INSTANCE.style().ensureInjected();
	}

	private void getOrders() {
		getBarsService.getOrders(1,
				new AsyncCallback<HashMap<Integer, String>>() {

					@Override
					public void onFailure(Throwable caught) {
						System.out.println("mist");
					}

					@Override
					public void onSuccess(HashMap<Integer, String> result) {
						for (final Integer orderVal : result.keySet()) {
							final Label content = new Label(result.get(orderVal));
							content.addStyleName("order" + orderVal);
							
							final Button done = new Button("Erledigt", new ClickHandler() {
								
								@Override
								public void onClick(ClickEvent event) {
									// ignore
								}
							});
							done.addClickHandler(new ClickHandler() {
								
								@Override
								public void onClick(ClickEvent event) {
									getBarsService.completeOrder(orderVal, new AsyncCallback<Boolean>() {

										@Override
										public void onFailure(Throwable caught) {
											System.out.println("*NOT* cool");
										}

										@Override
										public void onSuccess(Boolean completed) {
											if (completed) {
												orderList.remove(content);
												orderList.remove(done);
											} else {
												// ignore for now
											}
										}
									});
								}
							});
							HorizontalPanel horizontal = new HorizontalPanel();
							horizontal.setSpacing(8);
							orderList.add(horizontal,"orderList");
							horizontal.add(done);
							horizontal.add(content);
						}
					}
				});
	}
	
	void logoutOnClick(ClickEvent e){
		RootPanel.get().remove(this);
		RootPanel.get().add(exitWidget);
	}

	public void setExitWidget(Widget exitWidget) {
		this.exitWidget = exitWidget;
	}
}
