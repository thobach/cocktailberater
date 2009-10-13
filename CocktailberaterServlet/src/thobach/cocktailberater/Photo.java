package thobach.cocktailberater;

public class Photo {
	private String url;

	public Photo(String url) {
		setUrl(url);
	}

	public void setUrl(String url) {
		this.url = url;
	}

	public String getUrl() {
		return url;
	}

	@Override
	public String toString() {
		return "<img src=\"" + getUrl() + "\" alt=\"Foto\" style=\"height: 100px;\" align=\"left\" />";
	}
}
