package net.phpgedview.pgv_lang;

import java.io.Serializable;

import org.eclipse.core.resources.IFile;
import org.eclipse.core.resources.IMarker;
import org.eclipse.core.runtime.CoreException;
import org.eclipse.core.runtime.Path;

public class PGVLangReference implements Serializable {
	/**
	 * 
	 */
	private static final long serialVersionUID = -4103054530339624508L;
	String filePath;
	int lineNumber;
	long markerId = 0;
	
	public PGVLangReference(IFile file, int lineNumber) {
		this.filePath = file.getFullPath().toPortableString();
		this.lineNumber = lineNumber;
	}
	
	public int getLineNumber() {
		return lineNumber;
	}
	public void setLineNumber(int lineNumber) {
		this.lineNumber = lineNumber;
	}
	
	public void addMarker(IFile file, String message, int severity) {
		try {
			IFile rfile = file.getProject().getFile(Path.fromPortableString(getFilePath()).removeFirstSegments(1));
			if (markerId>0) {
				IMarker m = rfile.findMarker(markerId);
				if (m!=null) return;
			}
			IMarker marker = rfile.createMarker(Activator.MARKER_TYPE);
			markerId = marker.getId();
			marker.setAttribute(IMarker.MESSAGE, message);
			marker.setAttribute(IMarker.SEVERITY, severity);
			if (lineNumber == -1) {
				lineNumber = 1;
			}
			marker.setAttribute(IMarker.LINE_NUMBER, lineNumber);
		} catch (CoreException e) {
			e.printStackTrace();
		}
	}
	
	public boolean equals(Object o) {
		PGVLangReference ref = (PGVLangReference) o;
		if (ref.getLineNumber()==lineNumber && filePath.equals(ref.getFilePath())) return true;
		return false;
	}

	public String getFilePath() {
		return filePath;
	}

	public void setFilePath(String filePath) {
		this.filePath = filePath;
	}
}
