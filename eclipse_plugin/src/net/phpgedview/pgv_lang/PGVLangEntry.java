package net.phpgedview.pgv_lang;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.eclipse.core.resources.IFile;
import org.eclipse.core.resources.IMarker;

public class PGVLangEntry implements Serializable {
	/**
	 * 
	 */
	private static final long serialVersionUID = 988197258245429203L;
	String key;
	String value;
	boolean delta;
	
	ArrayList<PGVLangReference> references;
	ArrayList<PGVLangReference> definitions;
	
	public PGVLangEntry(String key, String value) {
		this.key = key;
		this.value = value;
		references = new ArrayList<PGVLangReference>();
		definitions = new ArrayList<PGVLangReference>();
		delta = true;
	}

	public String getKey() {
		return key;
	}

	public void setKey(String key) {
		this.key = key;
	}

	public List<PGVLangReference> getReferences() {
		return references;
	}

	public void setReferences(ArrayList<PGVLangReference> references) {
		this.references = references;
		delta = true;
	}

	public String getValue() {
		return value;
	}

	public void setValue(String value) {
		this.value = value;
		delta = true;
	}
	
	public void removeFileReferences(IFile file) {
		Iterator i = references.iterator();
		while(i.hasNext()) {
			PGVLangReference r = (PGVLangReference) i.next();
			if (file.getFullPath().toPortableString().equals(r.getFilePath())) i.remove();
		}
		
		i = definitions.iterator();
		while(i.hasNext()) {
			PGVLangReference r = (PGVLangReference) i.next();
			if (file.getFullPath().toPortableString().equals(r.getFilePath())) i.remove();
		}
		delta = true;
	}
	
	public void addReference(PGVLangReference ref) {
		if (!references.contains(ref) && !definitions.contains(ref) ) {
			references.add(ref);
			delta = true;
		}
	}

	public ArrayList<PGVLangReference> getDefinitions() {
		return definitions;
	}

	public void setDefinitions(ArrayList<PGVLangReference> definitions) {
		this.definitions = definitions;
		delta = true;
	}
	
	public void addDefinition(PGVLangReference ref) {
		if (!definitions.contains(ref)) {
			definitions.add(ref);
			delta = true;
		}
	}
	
	public boolean hasReferences() {
		return !references.isEmpty();
	}
	
	public boolean hasDefinitions() {
		return !definitions.isEmpty();
	}
	
	public void checkMarkers(IFile file) {
//		if (!delta) return;
//		delta = false;

		//-- error if the entry is not defined
		if (!hasDefinitions()) {
			Iterator i = references.iterator();
			while(i.hasNext()) {
				PGVLangReference r = (PGVLangReference) i.next();
				r.addMarker(file, "Language Entry "+key+" is never defined.", IMarker.SEVERITY_ERROR);
			}
		}
		
		//-- error if the entry is never used
		if (!hasReferences()) {
			Iterator i = definitions.iterator();
			while(i.hasNext()) {
				PGVLangReference r = (PGVLangReference) i.next();
				r.addMarker(file, "Language Entry "+key+" is defined but never used.", IMarker.SEVERITY_ERROR);
			}
		}
		
		//-- warning if the entry is defined more than once
		if (definitions.size()>1) {
			Iterator i = definitions.iterator();
			PGVLangReference or = (PGVLangReference) i.next();
			String message = "  Previous definition at "+or.getFilePath()+" line "+or.getLineNumber();
			while(i.hasNext()) {
				PGVLangReference r = (PGVLangReference) i.next();
				String fname = r.getFilePath();
				Pattern p = Pattern.compile("\\.en\\.php");
				Matcher m = p.matcher(fname);
				if (!m.find()) r.addMarker(file, "Duplicate Language definition for "+key+message, IMarker.SEVERITY_WARNING);
			}
		}
	}
	
	public void clearReferences(IFile file) {
		Iterator i = references.iterator();
		while(i.hasNext()) {
			PGVLangReference r = (PGVLangReference) i.next();
			if (file.getFullPath().toPortableString().equals(r.getFilePath())) i.remove();
		}
	
		i = definitions.iterator();
		while(i.hasNext()) {
			PGVLangReference r = (PGVLangReference) i.next();
			if (file.getFullPath().toPortableString().equals(r.getFilePath())) i.remove();
		}
	}
}
