package org.cocktailberater.de.shared.client;

import com.google.gwt.core.client.GWT;
import com.google.gwt.resources.client.ClientBundle;
import com.google.gwt.resources.client.CssResource;
import com.google.gwt.resources.client.ImageResource;

public interface Resources extends ClientBundle {
	public static final Resources INSTANCE = GWT.create(Resources.class);

	@Source("Shared.css")
	Style style();

	@Source("header-detail.png")
	ImageResource logo();
	
	@Source("general-button-small4.png")
	ImageResource button();

	@Source("general-button.png")
	ImageResource longbutton();
	
	public interface Style extends CssResource {
		String container();
		
		String smallContainer();
		
		String mediumContainer();

		String required();
		
		String pink();
		
		String large();
		
		@ClassName("label")
		String label();
		
		@ClassName("button")
		String button();
		
		@ClassName("longbutton")
		String longbutton();
	}

}
