package net.phpgedview.pgv_lang.builder;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.Map;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import net.phpgedview.pgv_lang.Activator;
import net.phpgedview.pgv_lang.PGVLangEntry;
import net.phpgedview.pgv_lang.PGVLangMap;
import net.phpgedview.pgv_lang.PGVLangReference;

import org.eclipse.core.resources.IFile;
import org.eclipse.core.resources.IProject;
import org.eclipse.core.resources.IResource;
import org.eclipse.core.resources.IResourceDelta;
import org.eclipse.core.resources.IResourceDeltaVisitor;
import org.eclipse.core.resources.IResourceVisitor;
import org.eclipse.core.resources.IncrementalProjectBuilder;
import org.eclipse.core.runtime.CoreException;
import org.eclipse.core.runtime.IProgressMonitor;
import org.eclipse.core.runtime.IStatus;
import org.eclipse.core.runtime.Status;

public class PgvLangBuilder extends IncrementalProjectBuilder {

	class SampleDeltaVisitor implements IResourceDeltaVisitor {
		/*
		 * (non-Javadoc)
		 * 
		 * @see org.eclipse.core.resources.IResourceDeltaVisitor#visit(org.eclipse.core.resources.IResourceDelta)
		 */
		public boolean visit(IResourceDelta delta) throws CoreException {
			IResource resource = delta.getResource();
			switch (delta.getKind()) {
			case IResourceDelta.ADDED:
				// handle added resource
				extractLangReferences(resource);
				break;
			case IResourceDelta.REMOVED:
				// handle removed resource
				PGVLangMap entries = PGVLangMap.getInstance(delta.getProjectRelativePath());
				if (resource instanceof IFile) entries.removeFileReferences((IFile)resource);
				break;
			case IResourceDelta.CHANGED:
				// handle changed resource
				extractLangReferences(resource);
				break;
			}
			//return true to continue visiting children.
			return true;
		}
	}

	class SampleResourceVisitor implements IResourceVisitor {
		public boolean visit(IResource resource) throws CoreException {
			extractLangReferences(resource);
			//return true to continue visiting children.
			return true;
		}
	}

	public static final String BUILDER_ID = "net.phpgedview.pgv_lang.PgvLangBuilder";

	/*
	 * (non-Javadoc)
	 * 
	 * @see org.eclipse.core.internal.events.InternalBuilder#build(int,
	 *      java.util.Map, org.eclipse.core.runtime.IProgressMonitor)
	 */
	protected IProject[] build(int kind, Map args, IProgressMonitor monitor)
			throws CoreException {
		if (kind == FULL_BUILD) {
			fullBuild(monitor);
		} else {
			IResourceDelta delta = getDelta(getProject());
			if (delta == null) {
				fullBuild(monitor);
			} else {
				incrementalBuild(delta, monitor);
			}
		}
		return null;
	}

	void extractLangReferences(IResource resource) throws CoreException {
		if (resource instanceof IFile && (resource.getName().endsWith(".php")
				|| resource.getName().endsWith(".html"))) {
			IFile file = (IFile) resource;
			PGVLangMap entries = PGVLangMap.getInstance(file.getProjectRelativePath());
			try {
				entries.clearReferences(file);
				InputStream stream = file.getContents();
				BufferedReader reader = new BufferedReader(new InputStreamReader(stream));
				String line = reader.readLine();
				int l = 1;
				while(line!=null) {
					Pattern p = Pattern.compile("pgv_lang\\[([^\\]]+)\\]\\s*=\\s*\"([^\"]*)\"");
					Matcher m = p.matcher(line);
					while(m.find()) {
						String key = m.group(1);
						if (key.startsWith("\"")) key = key.substring(1, key.length()-1);
						String value = m.group(2);
						PGVLangReference ref = new PGVLangReference(file, l);
						PGVLangEntry e = entries.getEntry(key);
						if (e == null) {
							e = new PGVLangEntry(key, value);
							entries.addEntry(e);
						}
						else e.setValue(value);
						e.addDefinition(ref);
					}
					
					p = Pattern.compile("pgv_lang\\[([^\\]]+)\\]");
					m = p.matcher(line);
					while(m.find()) {
						String key = m.group(1);
						if (key.startsWith("\"")) key = key.substring(1, key.length()-1);
						else if (key.startsWith("'")) key = key.substring(1, key.length()-1);
						PGVLangReference ref = new PGVLangReference(file, l);
						PGVLangEntry e = entries.getEntry(key);
						if (e == null) {
							e = new PGVLangEntry(key, "");
							entries.addEntry(e);
						}
						e.addReference(ref);
					}	
			
					l++;
					line = reader.readLine();
				}
			}
			catch(IOException ioe) {
				throw new CoreException(new Status(IStatus.ERROR, Activator.PLUGIN_ID,1,ioe.getMessage(), ioe));
			}
			deleteMarkers(file);
			entries.setMarkers(file);
			/*XMLErrorHandler reporter = new XMLErrorHandler(file);
			try {
				getParser().parse(file.getContents(), reporter);
			} catch (Exception e1) {
			}*/
		}
	}
	
	private void deleteMarkers(IFile file) {
		try {
			file.deleteMarkers(Activator.MARKER_TYPE, false, IResource.DEPTH_ZERO);
		} catch (CoreException ce) {
		}
	}

	protected void fullBuild(final IProgressMonitor monitor)
			throws CoreException {
		getProject().accept(new SampleResourceVisitor());
	}

	protected void incrementalBuild(IResourceDelta delta,
			IProgressMonitor monitor) throws CoreException {
		// the visitor does the work.
		delta.accept(new SampleDeltaVisitor());
	}
}
