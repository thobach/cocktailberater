package org.cocktailberater.de.shared.server;

import java.io.OutputStreamWriter;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLConnection;
import java.net.URLEncoder;
import java.util.HashMap;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.cocktailberater.de.shared.client.BarService;
import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

import com.google.gwt.user.server.rpc.RemoteServiceServlet;

@SuppressWarnings("serial")
public class BarServiceImpl extends RemoteServiceServlet implements BarService {

	public static final int STATUS_CODE_OK = 200;

	private static String RECIPE_URL = Messages
			.getString("BarServiceImpl.RECIPE_URL");

	private static String ORDER_URL = Messages
			.getString("BarServiceImpl.ORDER_URL");

	public static String BAR_URL = Messages.getString("BarServiceImpl.BAR_URL");

	public static String PARTY_URL = Messages
			.getString("BarServiceImpl.PARTY_URL");

	public static String AUTH_URL = Messages
			.getString("BarServiceImpl.AUTH_URL");

	public static String MEMBER_URL = Messages
			.getString("BarServiceImpl.MEMBER_URL");

	private String hashCode;

	private String memberId;

	@Override
	public HashMap<Integer, String> getBars() {
		// init empty list of bars
		HashMap<Integer, String> barList = new HashMap<Integer, String>();
		try {
			// get content from url
			URL url = new URL(BAR_URL);
			URLConnection conn = url.openConnection();
			// parse xml content
			DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
			DocumentBuilder db = dbf.newDocumentBuilder();
			Document doc = db.parse(conn.getInputStream());
			// load node bars node (rsp -> bars)
			NodeList bars = doc.getFirstChild().getFirstChild().getChildNodes();
			for (int i = 0; i < bars.getLength(); i++) {
				// add name of the bar to the returned array list
				barList.put(Integer.parseInt(bars.item(i).getAttributes()
						.getNamedItem("id").getNodeValue()), bars.item(i)
						.getAttributes().getNamedItem("name").getNodeValue());
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
			String data = URLEncoder.encode("email", "UTF-8") + "="
					+ URLEncoder.encode(email, "UTF-8");
			data += "&" + URLEncoder.encode("password", "UTF-8") + "="
					+ URLEncoder.encode(password, "UTF-8");
			// get content from url
			URL url = new URL(AUTH_URL);
			URLConnection conn = url.openConnection();
			// enable http post
			conn.setDoOutput(true);
			OutputStreamWriter wr = new OutputStreamWriter(conn
					.getOutputStream());
			wr.write(data);
			wr.flush();
			// parse xml content
			DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
			DocumentBuilder db = dbf.newDocumentBuilder();
			Document doc = db.parse(conn.getInputStream());

			// load node rsp node
			Node rsp = doc.getFirstChild();
			hashCode = rsp.getFirstChild().getAttributes().getNamedItem(
					"hashCode").getNodeValue();
			System.out.println("new hash code: " + hashCode);
			memberId = rsp.getFirstChild().getAttributes().getNamedItem("id")
					.getNodeValue();
			System.out.println("new memberId: " + memberId);
			if ((rsp.getAttributes().getNamedItem("status").getNodeValue())
					.equals("ok")) {
				authenticated = true;
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		return authenticated;
	}

	@Override
	public HashMap<Integer, String> getRecipes() {
		// init empty list of recipes
		HashMap<Integer, String> recipeList = new HashMap<Integer, String>();
		try {
			// get content from url
			URL url = new URL(RECIPE_URL);
			URLConnection conn = url.openConnection();
			// parse xml content
			DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
			DocumentBuilder db = dbf.newDocumentBuilder();
			Document doc = db.parse(conn.getInputStream());
			// load node menue node (rsp -> menue)
			NodeList recipes = doc.getFirstChild().getFirstChild()
					.getChildNodes();
			for (int i = 0; i < recipes.getLength(); i++) {
				// add name of the recipe to the returned array list
				recipeList.put(Integer.parseInt(recipes.item(i).getAttributes()
						.getNamedItem("id").getNodeValue()), recipes.item(i)
						.getAttributes().getNamedItem("name").getNodeValue());
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
			String data = URLEncoder.encode("hashCode", "UTF-8") + "="
					+ URLEncoder.encode(hashCode, "UTF-8");
			data += "&" + URLEncoder.encode("recipeId", "UTF-8") + "="
					+ URLEncoder.encode(recipeId.toString(), "UTF-8");
			data += "&" + URLEncoder.encode("partyId", "UTF-8") + "="
					+ URLEncoder.encode(partyId.toString(), "UTF-8");
			data += "&" + URLEncoder.encode("memberId", "UTF-8") + "="
					+ URLEncoder.encode(memberId.toString(), "UTF-8");
			data += "&" + URLEncoder.encode("format", "UTF-8") + "="
					+ URLEncoder.encode("xml", "UTF-8");
			// get content from url
			URL url = new URL(ORDER_URL.replaceAll("/:id", "").replaceAll("/:member", "").replaceAll(
					"/:party", ""));
			URLConnection conn = url.openConnection();
			// enable http post
			conn.setDoOutput(true);
			OutputStreamWriter wr = new OutputStreamWriter(conn
					.getOutputStream());
			wr.write(data);
			wr.flush();
			// parse xml content
			DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
			DocumentBuilder db = dbf.newDocumentBuilder();
			Document doc = db.parse(conn.getInputStream());

			// load node rsp node
			Node rsp = doc.getFirstChild();
			if ((rsp.getAttributes().getNamedItem("status").getNodeValue())
					.equals("ok")) {
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
		HashMap<Integer, String> partyList = new HashMap<Integer, String>();
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
				if (Integer.parseInt(bars.item(i).getAttributes().getNamedItem(
						"barId").getNodeValue()) == barId) {
					// add name of the bar to the returned array list
					partyList.put(Integer.parseInt(bars.item(i).getAttributes()
							.getNamedItem("id").getNodeValue()), bars.item(i)
							.getAttributes().getNamedItem("name")
							.getNodeValue());
				}
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		return partyList;
	}

	@Override
	public HashMap<Integer, String> getOrders(Integer partyId) {
		// init empty list of orders
		HashMap<Integer, String> orderList = new HashMap<Integer, String>();
		try {
			// get content from url
			URL url = new URL(ORDER_URL.replaceAll("/:id", "").replaceAll(
					"/:member", "").replaceAll("/:party", "/party/" + partyId));
			System.out.println(url.toExternalForm());
			URLConnection conn = url.openConnection();
			// parse xml content
			DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
			DocumentBuilder db = dbf.newDocumentBuilder();
			Document doc = db.parse(conn.getInputStream());
			// load node bars node (rsp -> orders)
			NodeList orders = doc.getFirstChild().getFirstChild()
					.getChildNodes();
			for (int i = 0; i < orders.getLength(); i++) {
				// add name of the bar to the returned array list
				int orderId = Integer.parseInt(orders.item(i).getAttributes()
						.getNamedItem("id").getNodeValue());
				String orderString = orders.item(i).getAttributes()
						.getNamedItem("recipeName").getNodeValue();
				orderString += " fŸr "
						+ getMemberName(Integer.parseInt(orders.item(i)
								.getAttributes().getNamedItem("member")
								.getNodeValue()));
				orderString += " um "
						+ orders.item(i).getAttributes().getNamedItem(
								"orderDate").getNodeValue();
				if (!orders.item(i).getAttributes().getNamedItem("comment")
						.getNodeValue().equals("")) {
					orderString += " ("
							+ orders.item(i).getAttributes().getNamedItem(
									"comment").getNodeValue() + ")";
				}
				orderList.put(orderId, orderString);
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		return orderList;
	}

	@Override
	public String getMemberName(Integer memberId) {
		String fullName = "";
		try {
			// get content from url
			URL url = new URL(MEMBER_URL.replaceAll(":id", memberId.toString()));
			URLConnection conn = url.openConnection();
			// parse xml content
			DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
			DocumentBuilder db = dbf.newDocumentBuilder();
			Document doc = db.parse(conn.getInputStream());
			// load node member
			fullName = doc.getFirstChild().getFirstChild().getAttributes()
					.getNamedItem("firstname").getNodeValue()
					+ " "
					+ doc.getFirstChild().getFirstChild().getAttributes()
							.getNamedItem("lastname").getNodeValue();
		} catch (Exception e) {
			e.printStackTrace();
		}
		return fullName;
	}

	@Override
	public boolean completeOrder(Integer orderId) {
		// init return variable
		boolean successful = false;
		try {
			// put data
			String data = URLEncoder.encode("hashCode", "UTF-8") + "="
					+ URLEncoder.encode(hashCode, "UTF-8");
			data += "&" + URLEncoder.encode("status", "UTF-8") + "="
					+ URLEncoder.encode("COMPLETED", "UTF-8");
			data += "&" + URLEncoder.encode("format", "UTF-8") + "="
					+ URLEncoder.encode("xml", "UTF-8");
			// get content from url
			URL url = new URL(ORDER_URL.replaceAll(":id", orderId.toString())
					.replaceAll("/:member", "").replaceAll("/:party", ""));
			System.out.println(url.toExternalForm());
			HttpURLConnection conn = (HttpURLConnection) url.openConnection();
			// enable http put
			conn.setDoOutput(true);
			conn.setRequestMethod("PUT");
			conn.setInstanceFollowRedirects(false);
			OutputStreamWriter wr = new OutputStreamWriter(conn
					.getOutputStream());
			wr.write(data);
			wr.flush();
			// parse xml content
			DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
			DocumentBuilder db = dbf.newDocumentBuilder();
			Document doc = db.parse(conn.getInputStream());

			// load node rsp node
			Node rsp = doc.getFirstChild();
			if ((rsp.getAttributes().getNamedItem("status").getNodeValue())
					.equals("ok")) {
				successful = true;
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		return successful;
	}
}
