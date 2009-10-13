package thobach.cocktailberater;

import java.io.IOException;
import java.io.InputStream;
import java.net.MalformedURLException;
import java.net.URL;

import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;

import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xml.sax.SAXException;

import com.google.apphosting.api.DatastorePb.GetResponse;

public class Cocktail {
	private int id;
	private String name;
	private String description;
	private String instruction;

	public int getId() {
		return id;
	}

	public void setId(int id) {
		this.id = id;
	}

	public String getInstruction() {
		return instruction.replace("\\", "");
	}

	public void setName(String name) {
		this.name = name;
	}

	public String getName() {
		return name.replace("\\", "");
	}

	public void setDescription(String description) {
		this.description = description;
	}

	public String getDescription() {
		if (description == null || description.equals("")) {
			return "keine Beschreibung";
		} else {
			return description.replace("\\", "");
		}
	}

	public Cocktail(int id, String name, String description, String instruction) {
		setId(id);
		setName(name);
		setDescription(description);
		setInstruction(instruction);
	}

	private void setInstruction(String instruction) {
		this.instruction = instruction;
	}

	public static Cocktail[] getAllFromServer() throws Exception {
		NodeList recipes = getRecipes();
		Cocktail[] cocktails = new Cocktail[recipes.getLength()];
		for (int i = 0; i < recipes.getLength(); i++) {
			Node recipe = ((Node) recipes).getChildNodes().item(i);
			cocktails[i] = map(recipe);
		}
		return cocktails;
	}

	public Photo getPhoto() throws Exception {
		if (getId() < 1) {
			throw new Exception("ID leer");
		} else {
			NodeList recipe = getRecipe(getId());
			if(recipe.item(3).getChildNodes().getLength()>0){
			return new Photo(recipe.item(3).getChildNodes().item(0).getAttributes().getNamedItem("url").getNodeValue());
			} else {
				return null;
			}
		}
	}

	private static Cocktail map(Node recipe) {
		int id = Integer.parseInt(recipe.getAttributes().getNamedItem("id")
				.getNodeValue());
		String name = recipe.getAttributes().getNamedItem("name")
				.getNodeValue();
		String description = recipe.getAttributes().getNamedItem("description")
				.getNodeValue();
		String instruction = recipe.getAttributes().getNamedItem("instruction")
				.getNodeValue();
		return new Cocktail(id, name, description, instruction);
	}

	private static NodeList getRecipes() throws Exception {
		String url = "http://api-stage.cocktailberater.de/recipe/get-all";
		try {
			URL server = new URL(url);
			InputStream is = (InputStream) server.getContent();
			Document doc = DocumentBuilderFactory.newInstance()
					.newDocumentBuilder().parse(is);
			Node rsp = doc.getChildNodes().item(0);
			NodeList recipes = rsp.getChildNodes().item(0).getChildNodes();
			return recipes;
		} catch (MalformedURLException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		} catch (SAXException e) {
			e.printStackTrace();
		} catch (ParserConfigurationException e) {
			e.printStackTrace();
		}
		throw new Exception("Recipies could not be loaded");
	}

	private static NodeList getRecipe(int id) {
		// TODO: check if id is valid (above 0, etc.)
		String url = "http://api-stage.cocktailberater.de/recipe/get/id/" + id;
		try {
			URL server = new URL(url);
			InputStream is = (InputStream) server.getContent();
			Document doc = DocumentBuilderFactory.newInstance()
					.newDocumentBuilder().parse(is);
			Node rsp = doc.getChildNodes().item(0);
			NodeList recipe = rsp.getChildNodes().item(0).getChildNodes();
			return recipe;
		} catch (MalformedURLException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		} catch (SAXException e) {
			e.printStackTrace();
		} catch (ParserConfigurationException e) {
			e.printStackTrace();
		}
		return null;
	}

}
