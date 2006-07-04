package net.phpgedview.pgv_lang;

import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.io.Serializable;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;

import org.eclipse.core.resources.IFile;

public class PGVLangMap implements Serializable{
	/**
	 * 
	 */
	private static final long serialVersionUID = -1939521442107573918L;
	private HashMap<String,PGVLangEntry> entries;
	private static PGVLangMap singleton;
	
	public PGVLangMap() {
		entries = new HashMap<String,PGVLangEntry>();
		singleton = this;
	}
	
	public static void writeMap() {
		if (singleton==null) return;
		try {
			ObjectOutputStream out = new ObjectOutputStream(new FileOutputStream(".pgvlang"));
			out.writeObject(singleton);
			out.close();
		} catch (FileNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} 
	}
	
	public static PGVLangMap getInstance() {
		if (singleton!=null) return singleton;
		try {
			ObjectInputStream in = new ObjectInputStream(new FileInputStream(".pgvlang"));
			singleton = (PGVLangMap) in.readObject();
			in.close();
			if (singleton!=null) return singleton;
		}
		catch(FileNotFoundException e) {
			
		}
		catch(IOException e) {
			e.printStackTrace();
		} catch (ClassNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		singleton = new PGVLangMap();
		return singleton;
	}
	
	public HashMap<String,PGVLangEntry> getEntries() {
		return entries;
	}
	
	public void addEntry(PGVLangEntry entry) {
		if (entries==null) entries = new HashMap<String,PGVLangEntry>();
		PGVLangEntry e = (PGVLangEntry) entries.get(entry.getKey());
		if (e==null) {
			entries.put(entry.getKey(), entry);
		}
		else {
			List refs = entry.getReferences();
			Iterator i = refs.iterator();
			while(i.hasNext()) {
				PGVLangReference ref = (PGVLangReference) i.next();
				e.addReference(ref);
			}
		}
	}
	
	public void removeFileReferences(IFile file) {
		if (entries==null) entries = new HashMap<String,PGVLangEntry>();
		Iterator i = entries.values().iterator();
		while(i.hasNext()) {
			PGVLangEntry e = (PGVLangEntry) i.next();
			e.removeFileReferences(file);
		}
	}
	
	public PGVLangEntry getEntry(String key) {
		if (entries==null) entries = new HashMap<String,PGVLangEntry>();
		return entries.get(key);
	}
	
	public void setMarkers(IFile file) {
		if (entries==null) entries = new HashMap<String,PGVLangEntry>();
		Iterator i = entries.values().iterator();
		while(i.hasNext()) {
			PGVLangEntry e = (PGVLangEntry) i.next();
			if (!e.hasDefinitions() && !e.hasReferences()) i.remove();
			else e.checkMarkers(file);
		}
		writeMap();
	}
	
	public void clearReferences(IFile file) {
		if (entries==null) entries = new HashMap<String,PGVLangEntry>();
		Iterator i = entries.values().iterator();
		while(i.hasNext()) {
			PGVLangEntry e = (PGVLangEntry) i.next();
			e.clearReferences(file);
			if (!e.hasDefinitions() && !e.hasReferences()) i.remove();
		}
		writeMap();
	}
}
