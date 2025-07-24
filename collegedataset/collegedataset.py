import pandas as pd

# Load the dataset
file_path = "All_Colleges.xlsx"  # Ensure this path is correct
colleges_df = pd.read_excel(file_path)

# Check if the column name matches exactly with your dataset
# For example, if the column name is 'College Name', use that
if 'College_Name' not in colleges_df.columns:
    print("Column 'College_Name' not found in the dataset.")
else:
    # Create a base URL for search (Google search in this case)
    base_url = "https://www.google.com/search?q="

    # Generate the search links
    colleges_df['College Link'] = base_url + colleges_df['College_Name'].str.replace(' ', '+')

    # Save the updated dataset
    colleges_df.to_excel('updated_colleges_with_links.xlsx', index=False)
    print("File saved successfully.")
