package thobach.cocktailberater;

import java.io.IOException;
import java.io.PrintWriter;

import javax.servlet.http.*;

@SuppressWarnings("serial")
public class CocktailberaterServlet extends HttpServlet {
	public void doGet(HttpServletRequest req, HttpServletResponse resp)
			throws IOException {
		PrintWriter out = resp.getWriter();
		resp.setContentType("text/html");
		out.println("<html><head><title>Cocktailberater</title></head><body>");
		out.println("<h1>Cocktailberater Servlet</h1>");
		Cocktail[] cocktails;
		try {
			cocktails = Cocktail.getAllFromServer();
			out.println("<ul>");
			for (Cocktail cocktail : cocktails) {
				String photoTag = "";
				try {
					Photo photo = cocktail.getPhoto();
					if(photo!=null){
						photoTag = photo.toString();
					}
				} catch (Exception e) {
					e.printStackTrace();
				}
				out.println("<li style=\"height: 320px; float: left; width: 300px; margin-right: 2em; margin-bottom: 2em; overflow: auto;\"><p><strong>" + cocktail.getName() + "</strong><br />" + photoTag +  "<em>" + cocktail.getDescription() + "</em><br />" + cocktail.getInstruction() + "</p></li>");
			}
			out.println("</ul>");
		} catch (Exception e1) {
			out.println(e1.getStackTrace());
		}
		out.println("</body></html>");
		;
	}
}
