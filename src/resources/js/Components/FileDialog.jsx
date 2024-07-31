import { Button } from "@/Components/ui/button";
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/Components/ui/dialog";
import { ScrollArea } from "@/Components/ui/scroll-area";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/Components/ui/tabs";
import React, { useState } from "react";
import axios from "axios";
import PropTypes from "prop-types";
import FileUpload from "./FileUpload";
import FileTemplate from "./FileTemplate";

export default function FileDialog({
  documents: initialDocuments = [],
  selectedFiles = [],
  setSelectedFiles,
  apiUrl,
  children,
  multiple = false,
}) {
  const [documents, setDocuments] = useState(initialDocuments);
  const [files, setFiles] = useState([]);
  const [tempFiles, setTempFiles] = useState(selectedFiles);
  const [open, setOpen] = useState(false);
  const [uploadedDocuments, setUploadedDocuments] = useState([]);
  const [tab, setTab] = useState("storage");

  const handleClick = (file) => {
    const isFileInTemp = tempFiles.some(
      (tempFile) => tempFile.path === file.path
    );
    const updateTempFiles = (newFiles) => setTempFiles(newFiles);

    if (!multiple) {
      updateTempFiles(isFileInTemp ? [] : [file]);
    } else {
      updateTempFiles(
        isFileInTemp
          ? tempFiles.filter((tempFile) => tempFile.path !== file.path)
          : [...tempFiles, file]
      );
    }
  };

  const handleSubmit = () => {
    setFiles([]);
    setSelectedFiles(tempFiles);
    setOpen(false);
  };

  const handleCancel = () => {
    setFiles([]);
    setTempFiles(selectedFiles);
    setOpen(false);
  };

  const onSubmit = async (e) => {
    e.preventDefault();
    const formData = new FormData();
    files.forEach((file) => formData.append("files[]", file.document));

    try {
      const response = await axios.post(apiUrl, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });

      if (response.data.success) {
        const newDocuments = response.data.documents;
        setUploadedDocuments((prevFiles) => [...prevFiles, ...newDocuments]);
        setFiles([]);

        if (!multiple) {
          setTempFiles([newDocuments[0]]);
        } else {
          setTempFiles((prev) => [...prev, ...newDocuments]);
        }
        setTab("storage");
      }
    } catch (error) {
      console.log("Error uploading files:", error);
    }
  };

  const fetchData = async (url) => {
    try {
      const response = await axios.get(url);
      setDocuments(response.data);
    } catch (error) {
      console.error("Error fetching documents:", error);
    }
  };

  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogTrigger asChild>
        {children || (
          <Button className="px-4 py-2 cursor-pointer text-sm rounded-full font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
            Select
          </Button>
        )}
      </DialogTrigger>
      <DialogContent className="max-w-[425px] sm:max-w-screen-sm">
        <DialogHeader>
          <DialogTitle>Choose Documents</DialogTitle>
          <DialogDescription>
            Select documents from your workspace to share with your team
          </DialogDescription>
        </DialogHeader>
        <Tabs
          value={tab}
          onValueChange={setTab}
          className="w-full min-h-[500px]"
        >
          <TabsList className="grid w-full grid-cols-2 rounded-b-none">
            <TabsTrigger value="storage">Storage</TabsTrigger>
            <TabsTrigger value="upload">Upload</TabsTrigger>
          </TabsList>
          <TabsContent
            value="storage"
            className="bg-[#F1F5F9] mt-0 h-[95%] rounded-b-md p-3 pr-0 dark:bg-muted focus-visible:ring-0 focus-visible:ring-offset-0"
          >
            <ScrollArea className="h-[430px] w-full">
              <div className="flex flex-col">
                {uploadedDocuments.length > 0 && (
                  <>
                    <h2>Uploaded Documents</h2>
                    <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 pr-3">
                      {uploadedDocuments.map((document) => (
                        <div
                          key={`uploaded-${document.id}`}
                          className="p-1 h-40 relative"
                          onClick={() => handleClick(document)}
                        >
                          <FileTemplate
                            type={document.type}
                            name={document.name}
                            url={document.path}
                            isSelected={true}
                          />
                          {tempFiles.some(
                            (tempFile) => tempFile.path === document.path
                          ) && (
                            <div className="absolute z-20 bottom-2.5 right-2 shadow-sm bg-white w-5 h-5 rounded-full flex items-center justify-center">
                              <span className="text-green-500">&#10003;</span>
                            </div>
                          )}
                        </div>
                      ))}
                    </div>
                  </>
                )}
                {documents.data.length > 0 && (
                  <>
                    <h2>Existing Documents</h2>
                    <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 pr-3">
                      {documents.data?.map((document) => (
                        <div
                          key={`existing-${document.id}`}
                          className="p-1 h-40 relative"
                          onClick={() => handleClick(document)}
                        >
                          <FileTemplate
                            type={document.type}
                            name={document.name}
                            url={document.path}
                            isSelected={true}
                          />
                          {tempFiles.some(
                            (tempFile) => tempFile.path === document.path
                          ) && (
                            <div className="absolute z-20 bottom-2.5 right-2 shadow-sm bg-white w-5 h-5 rounded-full flex items-center justify-center">
                              <span className="text-green-500">&#10003;</span>
                            </div>
                          )}
                        </div>
                      ))}
                    </div>
                  </>
                )}
              </div>
            </ScrollArea>
          </TabsContent>
          <TabsContent
            value="upload"
            className="bg-[#F1F5F9] mt-0 h-[95%] rounded-b-md p-2 dark:bg-muted focus-visible:ring-0 focus-visible:ring-offset-0"
          >
            <FileUpload files={files} setFiles={setFiles} />
          </TabsContent>
        </Tabs>
        <DialogFooter
          className={`${tab === "storage" && "sm:justify-end"} mt-2`}
        >
          {tab === "storage" && (
            <div className="flex flex-1 items-end">
              <nav className="text-center space-x-1">
                {documents.meta.links?.map((link) => (
                  <button
                    onClick={() => fetchData(link.url)}
                    key={link.label}
                    className={`inline-block py-2 px-3 rounded-lg text-gray-200 text-xs ${
                      link.active ? "bg-gray-950" : ""
                    } ${
                      !link.url
                        ? "!text-gray-500 cursor-not-allowed"
                        : "hover:bg-gray-950"
                    }`}
                    dangerouslySetInnerHTML={{
                      __html: link.label,
                    }}
                  ></button>
                ))}
              </nav>
            </div>
          )}
          <div className="flex flex-row gap-1">
            <DialogClose asChild>
              <Button
                type="button"
                variant="secondary"
                className="bg-emerald-500 text-white hover:bg-emerald-400 rounded-full"
                onClick={handleCancel}
              >
                NO
              </Button>
            </DialogClose>
            {tab === "storage" ? (
              <Button
                type="submit"
                className="bg-red-600 text-white hover:bg-red-500 rounded-full"
                onClick={handleSubmit}
              >
                YES
              </Button>
            ) : (
              <Button
                disabled={files.length === 0}
                onClick={onSubmit}
                type="submit"
                className="bg-blue-600 hover:bg-blue-700 active:bg-blue-600 rounded-full"
              >
                Upload
              </Button>
            )}
          </div>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}

FileDialog.propTypes = {
  selectedFiles: PropTypes.arrayOf(PropTypes.object),
  documents: PropTypes.object.isRequired,
  setSelectedFiles: PropTypes.func.isRequired,
  apiUrl: PropTypes.string.isRequired,
  multiple: PropTypes.bool,
  children: PropTypes.node,
};
