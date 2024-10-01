from flask import Flask, jsonify, request, abort

app = Flask(__name__)

# In-memory storage for tasks
tasks = []
task_id_counter = 1

# Retrieve all tasks
@app.route('/tasks', methods=['GET'])
def get_tasks():
    return jsonify(tasks), 200

# Create a new task
@app.route('/tasks', methods=['POST'])
def create_task():
    global task_id_counter
    data = request.json

    try:
        if not data or 'title' not in data:
            abort(400, 'Bad Request: Task must have a title.')

        if 'description' not in data:
            abort(400, 'Bad Request: Task must have a description.')

        if 'status' not in data:
            abort(400, 'Bad Request: Task must have a status.')

        new_task = {
            'id': task_id_counter,
            'title': data['title'],
            'description': data['description'],
            'status': data['status']
        }
        tasks.append(new_task)
        task_id_counter += 1

        return jsonify({
            'success': True,
            'message': 'Task '+data['title']+' has been successfully processed by Python',
        }), 201
    except Exception as e:
        import traceback
        print("Exception: ", str(e))
        print(traceback.format_exc())
        return jsonify({
            'success': False,
            'message': str(e),
        }), 500

# Update an existing task
@app.route('/tasks/<int:task_id>', methods=['PUT'])
def update_task(task_id):
    data = request.json

    try:
        task = next((task for task in tasks if task['id'] == task_id), None)
        if task is None:
            abort(404, 'Task not found.')

        if 'title' in data:
            task['title'] = data['title']
        if 'description' in data:
            task['description'] = data['description']
        if 'status' in data:
            task['status'] = data['status']

        return jsonify({
            'success': True,
            'message': 'Task has been successfully updated by Python',
        }), 200

    except Exception as e:
        import traceback
        print("Exception: ", str(e))
        print(traceback.format_exc())
        return jsonify({
            'success': False,
            'message': str(e),
        }), 500

# Delete a task
@app.route('/tasks/<int:task_id>', methods=['DELETE'])
def delete_task(task_id):
    global tasks

    try:
        task = next((task for task in tasks if task['id'] == task_id), None)

        if task is None:
            abort(404, 'Task not found.')

        tasks = [task for task in tasks if task['id'] != task_id]

        return jsonify({
            'success': True,
            'message': 'Task has been deleted by Python'
        }), 200

    except Exception as e:
        import traceback
        print("Exception: ", str(e))
        print(traceback.format_exc())
        return jsonify({
            'success': False,
            'message': str(e),
        }), 500

# Error handling
@app.errorhandler(404)
def not_found(error):
    return jsonify({'error': str(error)}), 404

@app.errorhandler(400)
def bad_request(error):
    return jsonify({'error': str(error)}), 400

if __name__ == '__main__':
    app.run(debug=True)
