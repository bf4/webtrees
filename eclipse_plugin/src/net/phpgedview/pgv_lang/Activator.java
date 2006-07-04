package net.phpgedview.pgv_lang;

import java.util.HashMap;
import java.util.Iterator;
import java.util.List;

import org.eclipse.core.resources.IFile;
import org.eclipse.jface.resource.ImageDescriptor;
import org.eclipse.ui.plugin.AbstractUIPlugin;
import org.osgi.framework.BundleContext;

/**
 * The activator class controls the plug-in life cycle
 */
public class Activator extends AbstractUIPlugin {

	// The plug-in ID
	public static final String PLUGIN_ID = "net.phpgedview.pgv_lang";
	public static final String MARKER_TYPE = "net.phpgedview.pgv_lang.pgvLangProblem";


	// The shared instance
	private static Activator plugin;
	private static HashMap<String,PGVLangEntry> entries;
	
	/**
	 * The constructor
	 */
	public Activator() {
		plugin = this;
		entries = new HashMap<String,PGVLangEntry>();
	}

	/*
	 * (non-Javadoc)
	 * @see org.eclipse.ui.plugin.AbstractUIPlugin#start(org.osgi.framework.BundleContext)
	 */
	public void start(BundleContext context) throws Exception {
		super.start(context);
	}

	/*
	 * (non-Javadoc)
	 * @see org.eclipse.ui.plugin.AbstractUIPlugin#stop(org.osgi.framework.BundleContext)
	 */
	public void stop(BundleContext context) throws Exception {
		plugin = null;
		super.stop(context);
	}

	/**
	 * Returns the shared instance
	 *
	 * @return the shared instance
	 */
	public static Activator getDefault() {
		return plugin;
	}

	/**
	 * Returns an image descriptor for the image file at the given
	 * plug-in relative path
	 *
	 * @param path the path
	 * @return the image descriptor
	 */
	public static ImageDescriptor getImageDescriptor(String path) {
		return imageDescriptorFromPlugin(PLUGIN_ID, path);
	}

	public static HashMap<String,PGVLangEntry> getEntries() {
		return entries;
	}
	
	public static void addEntry(PGVLangEntry entry) {
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
	
	public static void removeFileReferences(IFile file) {
		Iterator i = entries.values().iterator();
		while(i.hasNext()) {
			PGVLangEntry e = (PGVLangEntry) i.next();
			e.removeFileReferences(file);
		}
	}
	
	public static PGVLangEntry getEntry(String key) {
		return entries.get(key);
	}
	
	public static void setAllMarkers() {
		Iterator i = entries.values().iterator();
		while(i.hasNext()) {
			PGVLangEntry e = (PGVLangEntry) i.next();
			e.checkMarkers(null);
		}
	}
	public static void setMarkers(IFile file) {
		Iterator i = entries.values().iterator();
		while(i.hasNext()) {
			PGVLangEntry e = (PGVLangEntry) i.next();
			e.checkMarkers(file);
		}
	}
}
