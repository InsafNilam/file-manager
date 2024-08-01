import React, { useRef, useState } from "react";
import FileTemplate from "./FileTemplate";
import { ScrollArea } from "@/Components/ui/scroll-area";
import PropTypes from "prop-types";

export default function FileUpload({ files, setFiles, MAX_COUNT = 5 }) {
    const fileInputRef = useRef(null);

    const [counter, setCounter] = useState(0);
    const [isDraggedOver, setIsDraggedOver] = useState(false);

    const handleUploadFiles = (documents) => {
        const uploaded = [...files];
        let limitExceeded = false;

        documents.forEach((document) => {
            if (uploaded.length >= MAX_COUNT) {
                limitExceeded = true;
                return;
            }

            if (!uploaded.some((f) => f.document.name === document.name)) {
                const objectURL = URL.createObjectURL(document);
                uploaded.push({
                    document: document,
                    url: objectURL,
                });
            }
        });

        if (limitExceeded) {
            alert(`You can only upload ${MAX_COUNT} files at a time`);
        } else {
            setFiles(uploaded);
        }

        // Reset file input field to allow the same file to be selected again
        fileInputRef.current.value = null;
    };

    const handleFileEvent = (e) => {
        const files = Array.prototype.slice.call(e.target.files);
        handleUploadFiles(files);
    };

    const handleFileDelete = (file) => {
        const { url } = file;

        URL.revokeObjectURL(url);

        setFiles((files) => {
            return files.filter((file) => file.url !== url);
        });
    };

    const handleDrop = (e) => {
        e.preventDefault();
        handleUploadFiles(e.dataTransfer.files);
        setIsDraggedOver(false);
        setCounter(0);
    };

    const handleDragEnter = (e) => {
        e.preventDefault();
        if (e.dataTransfer.types.includes("Files")) {
            setCounter((prevCounter) => prevCounter + 1);
            setIsDraggedOver(true);
        }
    };

    const handleDragLeave = (e) => {
        setCounter((prevCounter) => prevCounter - 1);
        if (counter <= 1) {
            setIsDraggedOver(false);
        }
    };

    const handleDragOver = (e) => {
        if (e.dataTransfer.types.includes("Files")) {
            e.preventDefault();
        }
    };

    return (
        <div className="flex flex-col gap-2">
            <article
                aria-label="File Upload Modal"
                className={`w-full p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 py-8 flex flex-col justify-center items-center ${
                    isDraggedOver ? "" : ""
                }}`}
                onDrop={handleDrop}
                onDragOver={handleDragOver}
                onDragLeave={handleDragLeave}
                onDragEnter={handleDragEnter}
            >
                <p className="mb-3 font-semibold text-gray-900 flex flex-wrap justify-center">
                    <span>Drag and drop your</span>&nbsp;
                    <span>files anywhere or</span>
                </p>
                <input
                    id="file"
                    type="file"
                    name="files"
                    multiple
                    ref={fileInputRef}
                    className="hidden"
                    onChange={(e) => handleFileEvent(e)}
                />
                <label
                    htmlFor="file"
                    className="cursor-pointer flex items-center justify-center flex-col"
                >
                    <svg
                        className="w-12 h-12 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                        ></path>
                    </svg>
                    <span className="text-base leading-normal">
                        Select files
                    </span>
                </label>
            </article>
            <div className="space-y-2">
                <h1 className="font-semibold sm:text-lg text-gray-900">
                    To Upload
                </h1>
                <ScrollArea className="h-44 w-full">
                    <div className="h-full w-full pr-3">
                        {files.length === 0 ? (
                            <div className="text-center flex flex-col items-center justify-center">
                                <img
                                    className="mx-auto w-32"
                                    src="https://user-images.githubusercontent.com/507615/54591670-ac0a0180-4a65-11e9-846c-e55ffce0fe7b.png"
                                    alt="no data"
                                />
                                <span className="text-small text-gray-500">
                                    No files selected
                                </span>
                            </div>
                        ) : (
                            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                {files.map((file) => (
                                    <div key={file.url} className="p-1 h-40">
                                        <FileTemplate
                                            name={file.document.name}
                                            type={file.document.type}
                                            url={file.url}
                                            handleFileDelete={() =>
                                                handleFileDelete(file)
                                            }
                                        />
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>
                </ScrollArea>
            </div>
        </div>
    );
}

FileUpload.propTypes = {
    files: PropTypes.array.isRequired,
    setFiles: PropTypes.func.isRequired,
    MAX_COUNT: PropTypes.number,
};
