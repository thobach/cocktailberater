package org.cocktailberater.de.orderclient.server;

import java.io.OutputStreamWriter;
import java.net.URL;
import java.net.URLConnection;
import java.net.URLEncoder;
import java.util.HashMap;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.cocktailberater.de.orderclient.client.BarService;
import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

import com.google.gwt.user.server.rpc.RemoteServiceServlet;

@SuppressWarnings("serial")
public class BarServiceImpl extends RemoteServiceServlet implements BarService {

	public static final int STATUS_CODE_OK = 200;

	private static final String RECIPE_URL = "http://cocktailberater.local:10088/website/menue/1?format=xml";

	private static final String ORDER_URL = "http://cocktailberater.local:10088/website/order?format=xml";

	public static String MY_URL = "http://cocktailberater.local:10088/website/bar?format=xml";
	
	public static String PARTY_URL = "http://cocktailberater.local:10088/website/party?format=xml";

	public static String AUTH_URL = "http://cocktailberater.local:10088/website/member?format=xml";

	private String hashCode;

	private String memberId;
	
	public HashMap<Integer,String> getBars() {
		// init empty list of bars
		HashMap<Integer,String> barList = new HashMap<Integer,String>();
		try {
			// get content from url
			URL url = new URL(MY_URL);
			URLConnection conn = url.openConnection();
			// parse xml content
			DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
			DocumentBuilder db = dbf.newDocumentBuilder();
			Document doc = db.parse(conn.getInputStream());
			// load node bars node (rsp -> bars)
			NodeList bars = doc.getFirstChild().getFirstChild().getChildNodes();
			for (int i = 0; i < bars.getLength(); i++) {
				// add name of the bar to the returned array list
				barList.put(Integer.parseInt(bars.item(i).getAttributes().getNamedItem("id")
						.getNodeValue()),bars.item(i).getAttributes().getNamedItem("name")
						.getNodeValue());
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		return barList;
	}

	@Override
	public boolean authenticate(String email, String password) {
		// init return variable
		boolean authenticated = false;
		try {
			// post data
			String data = URLEncoder.encode("email", "UTF-8") + "=" + URLEncoder.encode(email, "UTF-8");
		    data += "&" + URLEncoder.encode("password", "UTF-8") + "=" + URLEncoder.encode(password, "UTF-8");
			// get content from url
			URL url = new URL(AUTH_URL);
			URLConnection conn = url.openConnection();
			// enable http post
			conn.setDoOutput(true);
		    OutputStreamWriter wr = new OutputStreamWriter(conn.getOutputStream());
		    wr.write(data);
		    wr.flush();
			// parse xml content
			DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
			DocumentBuilder db = dbf.newDocumentBuilder();
			Document doc = db.parse(conn.getInputStream());
			
			// load node rsp node
			Node rsp = doc.getFirstChild();
			hashCode = rsp.getFirstChild().getAttributes().getNamedItem("hashCode").getNodeValue();
			memberId = rsp.getFirstChild().getAttributes().getNamedItem("id").getNodeValue();
			if((rsp.getAttributes().getNamedItem("status").getNodeValue()).equals("ok")){
				authenticated = true;
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		return authenticated;
	}

	@Override
	public HashMap<Integer,String> getRecipes() {
		// init empty list of recipes
		HashMap<Integer,String> recipeList = new HashMap<Integer,String>();
		try {
			// get content from url
			URL url = new URL(RECIPE_URL);
			URLConnection conn = url.openConnection();
			// parse xml content
			DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
			DocumentBuilder db = dbf.newDocumentBuilder();
			Document doc = db.parse(conn.getInputStream());
			// load node menue node (rsp -> menue)
			NodeList recipes = doc.getFirstChild().getFirstChild().getChildNodes();
			for (int i = 0; i < recipes.getLength(); i++) {
				// add name of the recipe to the returned array list
				recipeList.put(Integer.parseInt(recipes.item(i).getAttributes().getNamedItem("id")
						.getNodeValue()),recipes.item(i).getAttributes().getNamedItem("name")
						.getNodeValue());
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		return recipeList;
	}

	@Override
	public boolean orderRecipe(Integer recipeId, Integer partyId) {
		// init return variable
		boolean successful = false;
		try {
			// post data
			String data = URLEncoder.encode("hashCode", "UTF-8") + "=" + URLEncoder.encode(hashCode, "UTF-8");
		    data += "&" + URLEncoder.encode("recipeId", "UTF-8") + "=" + URLEncoder.encode(recipeId.toString(), "UTF-8");
		    data += "&" + URLEncoder.encode("partyId", "UTF-8") + "=" + URLEncoder.encode(partyId.toString(), "UTF-8");
		    data += "&" + URLEncoder.encode("memberId", "UTF-8") + "=" + URLEncoder.encode(memberId.toString(), "UTF-8");
		    data += "&" + URLEncoder.encode("format", "UTF-8") + "=" + URLEncoder.encode("xml", "UTF-8");
			// get content from url
			URL url = new URL(ORDER_URL);
			URLConnection conn = url.openConnection();
			// enable http post
			conn.setDoOutput(true);
		    OutputStreamWriter wr = new OutputStreamWriter(conn.getOutputStream());
		    wr.write(data);
		    wr.flush();
			// parse xml content
			DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
			DocumentBuilder db = dbf.newDocumentBuilder();
			Document doc = db.parse(conn.getInputStream());
			
			// load node rsp node
			Node rsp = doc.getFirstChild();
			if((rsp.getAttributes().getNamedItem("status").getNodeValue()).equals("ok")){
				successful = true;
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		return successful;
	}

	@Override
	public HashMap<Integer, String> getPartys(Integer barId) {
		// init empty list of parties
		HashMap<Integer,String> partyList = new HashMap<Integer,String>();
		try {
			// get content from url
			URL url = new URL(PARTY_URL);
			URLConnection conn = url.openConnection();
			// parse xml content
			DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
			DocumentBuilder db = dbf.newDocumentBuilder();
			Document doc = db.parse(conn.getInputStream());
			// load node bars node (rsp -> partys)
			NodeList bars = doc.getFirstChild().getFirstChild().getChildNodes();
			for (int i = 0; i < bars.getLength(); i++) {
				// only add partys of the selected bar to the list
				if(Integer.parseInt(bars.item(i).getAttributes().getNamedItem("barId")
						.getNodeValue()) == barId){
				// add name of the bar to the returned array list
				partyList.put(Integer.parseInt(bars.item(i).getAttributes().getNamedItem("id")
						.getNodeValue()),bars.item(i).getAttributes().getNamedItem("name")
						.getNodeValue());
				}
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		return partyList;
	}
}
