import pandas as pd

# Load the CSV file
file_path = 'colleges.csv'  # Adjust the file path as necessary
colleges_df = pd.read_csv(file_path)

# Create the 'college_links' column with Google search URLs
colleges_df['college_links'] = colleges_df['college_name'].apply(
    lambda name: f"https://www.google.com/search?q={str(name).replace(' ', '+')}" if pd.notnull(name) else 'No Link Available'
)

# Save the updated DataFrame back to a CSV file
colleges_df.to_csv('updated_colleges.csv', index=False)

print("Updated CSV file saved as 'updated_colleges.csv'.")
