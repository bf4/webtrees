package net.phpgedview.pgv_lang;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.eclipse.core.resources.IFile;
import org.eclipse.core.resources.IMarker;

public class PGVLangEntry {
	String key;
	String value;
	
	ArrayList<PGVLangReference> references;
	ArrayList<PGVLangReference> definitions;
	
	public PGVLangEntry(String key, String value) {
		this.key = key;
		this.value = value;
		references = new ArrayList<PGVLangReference>();
		definitions = new ArrayList<PGVLangReference>();
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
	}

	public String getValue() {
		return value;
	}

	public void setValue(String value) {
		this.value = value;
	}
	
	public void removeFileReferences(IFile file) {
		Iterator i = references.iterator();
		while(i.hasNext()) {
			PGVLangReference r = (PGVLangReference) i.next();
			if (file==r.getFile()) i.remove();
		}
		
		i = definitions.iterator();
		while(i.hasNext()) {
			PGVLangReference r = (PGVLangReference) i.next();
			if (file==r.getFile()) i.remove();
		}
	}
	
	public void addReference(PGVLangReference ref) {
		references.add(ref);
	}

	public ArrayList<PGVLangReference> getDefinitions() {
		return definitions;
	}

	public void setDefinitions(ArrayList<PGVLangReference> definitions) {
		this.definitions = definitions;
	}
	
	public void addDefinition(PGVLangReference ref) {
		definitions.add(ref);
	}
	
	public boolean hasReferences() {
		return !references.isEmpty();
	}
	
	public boolean hasDefinitions() {
		return !definitions.isEmpty();
	}
	
	public void checkMarkers(IFile file) {
		//-- error if the entry is not defined
		if (!hasDefinitions()) {
			Iterator i = references.iterator();
			while(i.hasNext()) {
				PGVLangReference r = (PGVLangReference) i.next();
				if (file==null || file==r.getFile())
					r.addMarker("Language Entry "+key+" is never defined.", IMarker.SEVERITY_ERROR);
			}
		}
		
		//-- error if the entry is never used
		if (!hasReferences()) {
			Iterator i = definitions.iterator();
			while(i.hasNext()) {
				PGVLangReference r = (PGVLangReference) i.next();
				if (file==null || file==r.getFile())
					r.addMarker("Language Entry "+key+" is defined but never used.", IMarker.SEVERITY_ERROR);
			}
		}
		
		//-- warning if the entry is defined more than once
		if (definitions.size()>1) {
			Iterator i = definitions.iterator();
			i.next();
			while(i.hasNext()) {
				PGVLangReference r = (PGVLangReference) i.next();
				if (file==null || file==r.getFile()) {
					String fname = r.getFile().getName();
					Pattern p = Pattern.compile("\\.en\\.php");
					Matcher m = p.matcher(fname);
					if (!m.find()) r.addMarker("Duplicate Language definition for "+key, IMarker.SEVERITY_WARNING);
				}
			}
		}
	}
}
